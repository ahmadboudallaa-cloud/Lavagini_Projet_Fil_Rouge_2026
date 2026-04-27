<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // Afficher la liste des conversations
    public function index()
    {
        $user = $this->currentUser();

        $conversations = Conversation::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['user1', 'user2', 'lastMessage', 'commande'])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($conversation) use ($user) {
                $conversation->other_user = $conversation->getOtherUser($user->id);
                $conversation->unread_count = $conversation->unreadCount($user->id);

                return $conversation;
            });

        return view('chat.index', compact('conversations'));
    }

    // Afficher une conversation spécifique
    public function show($id)
    {
        $user = $this->currentUser();
        $conversation = $this->findUserConversation($user, $id);

        $this->markConversationMessagesAsRead($conversation, $user);

        $messages = Message::where('conversation_id', $conversation->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        $otherUser = $conversation->getOtherUser($user->id);

        return view('chat.show', compact('conversation', 'messages', 'otherUser'));
    }

    // Créer ou récupérer une conversation
    public function getOrCreateConversation(Request $request)
    {
        $user = $this->currentUser();
        $otherUserId = $request->input('user_id');
        $commandeId = $request->input('commande_id');

        // Vérifier si l'utilisateur peut créer cette conversation
        if (!$this->canCreateConversation($user, $otherUserId, $commandeId)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $conversationQuery = $this->conversationQueryBetweenUsers($user, $otherUserId);

        $conversation = $commandeId
            ? $conversationQuery->where('commande_id', $commandeId)->first()
            : $conversationQuery->whereNull('commande_id')->first();

        // Créer une nouvelle conversation si elle n'existe pas
        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $user->id,
                'user2_id' => $otherUserId,
                'commande_id' => $commandeId,
                'last_message_at' => now()
            ]);
        }

        return redirect()->route('chat.show', $conversation->id);
    }

    // Envoyer un message
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        $user = $this->currentUser();
        $conversation = $this->findUserConversation($user, $id);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender')
            ]);
        }

        return redirect()->back();
    }

    // Récupérer les nouveaux messages (AJAX)
    public function getNewMessages($id, Request $request)
    {
        $user = $this->currentUser();
        $lastMessageId = (int) $request->input('last_message_id', 0);
        $conversation = $this->findUserConversation($user, $id);

        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastMessageId)
            ->where('sender_id', '!=', $user->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        $this->markConversationMessagesAsRead($conversation, $user);

        return response()->json(['messages' => $messages]);
    }

    // Liste des utilisateurs disponibles pour le chat
    public function availableUsers()
    {
        $user = $this->currentUser();
        $users = collect();

        if ($user->isAdmin()) {
            $users = $this->availableUsersForAdmin($user);
        } elseif ($user->isLaveur()) {
            $users = $this->availableUsersForLaveur($user);
        } elseif ($user->isClient()) {
            $users = $this->availableUsersForClient($user);
        }

        return view('chat.available-users', compact('users'));
    }

    // Vérifier si l'utilisateur peut créer une conversation
    private function canCreateConversation($user, $otherUserId, $commandeId = null)
    {
        $otherUser = User::findOrFail($otherUserId);

        // Admin peut parler avec tout le monde
        if ($user->isAdmin()) {
            return true;
        }

        // Laveur peut parler avec un client de ses missions
        if ($user->isLaveur()) {
            if ($otherUser->isAdmin()) {
                return true;
            }

            if ($otherUser->isClient()) {
                $query = DB::table('missions')
                    ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
                    ->where('missions.laveur_id', $user->id)
                    ->where('commandes.client_id', $otherUser->id);

                return $commandeId
                    ? $query->where('missions.commande_id', $commandeId)->exists()
                    : $query->exists();
            }
        }

        // Client peut parler avec un laveur assigné à une de ses commandes
        if ($user->isClient()) {
            if ($otherUser->isLaveur()) {
                $query = DB::table('missions')
                    ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
                    ->where('commandes.client_id', $user->id)
                    ->where('missions.laveur_id', $otherUser->id);

                return $commandeId
                    ? $query->where('missions.commande_id', $commandeId)->exists()
                    : $query->exists();
            }
        }

        return false;
    }

    private function currentUser(): User
    {
        return Auth::user();
    }

    private function findUserConversation(User $user, $id): Conversation
    {
        return Conversation::with(['user1', 'user2', 'commande'])
            ->where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('user1_id', $user->id)
                    ->orWhere('user2_id', $user->id);
            })
            ->firstOrFail();
    }

    private function conversationQueryBetweenUsers(User $user, $otherUserId)
    {
        return Conversation::where(function ($query) use ($user, $otherUserId) {
                $query->where('user1_id', $user->id)
                    ->where('user2_id', $otherUserId);
            })
            ->orWhere(function ($query) use ($user, $otherUserId) {
                $query->where('user1_id', $otherUserId)
                    ->where('user2_id', $user->id);
            });
    }

    private function markConversationMessagesAsRead(Conversation $conversation, User $user): void
    {
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    private function availableUsersForAdmin(User $user)
    {
        return User::whereIn('role', ['laveur', 'client'])
            ->where('id', '!=', $user->id)
            ->get();
    }

    private function availableUsersForLaveur(User $user)
    {
        $adminIds = DB::table('missions')
            ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
            ->where('missions.laveur_id', $user->id)
            ->distinct()
            ->pluck('commandes.client_id');

        return User::where('role', 'admin')
            ->orWhereIn('id', $adminIds)
            ->where('id', '!=', $user->id)
            ->get();
    }

    private function availableUsersForClient(User $user)
    {
        $laveurIds = DB::table('missions')
            ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
            ->where('commandes.client_id', $user->id)
            ->distinct()
            ->pluck('missions.laveur_id');

        return User::whereIn('id', $laveurIds)->get();
    }
}
