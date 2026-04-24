<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    // Afficher la liste des conversations
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer toutes les conversations de l'utilisateur
        $conversations = Conversation::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['user1', 'user2', 'lastMessage', 'commande'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Ajouter les informations supplémentaires
        $conversations = $conversations->map(function($conversation) use ($user) {
            $conversation->other_user = $conversation->getOtherUser($user->id);
            $conversation->unread_count = $conversation->unreadCount($user->id);
            return $conversation;
        });

        return view('chat.index', compact('conversations'));
    }

    // Afficher une conversation spécifique
    public function show($id)
    {
        $user = Auth::user();
        
        $conversation = Conversation::with(['user1', 'user2', 'commande'])
            ->where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->orWhere('user2_id', $user->id);
            })
            ->firstOrFail();

        // Marquer les messages comme lus
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Récupérer les messages
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
        $user = Auth::user();
        $otherUserId = $request->input('user_id');
        $commandeId = $request->input('commande_id');

        // Vérifier si l'utilisateur peut créer cette conversation
        if (!$this->canCreateConversation($user, $otherUserId, $commandeId)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Chercher une conversation existante
        $conversation = Conversation::where(function($query) use ($user, $otherUserId) {
                $query->where('user1_id', $user->id)->where('user2_id', $otherUserId);
            })
            ->orWhere(function($query) use ($user, $otherUserId) {
                $query->where('user1_id', $otherUserId)->where('user2_id', $user->id);
            })
            ->where('commande_id', $commandeId)
            ->first();

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

        $user = Auth::user();
        
        $conversation = Conversation::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->orWhere('user2_id', $user->id);
            })
            ->firstOrFail();

        // Créer le message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false
        ]);

        // Mettre à jour la date du dernier message
        $conversation->update(['last_message_at' => now()]);

        if ($request->ajax()) {
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
        $user = Auth::user();
        $lastMessageId = $request->input('last_message_id', 0);

        $conversation = Conversation::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('user1_id', $user->id)
                      ->orWhere('user2_id', $user->id);
            })
            ->firstOrFail();

        $messages = Message::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer comme lus
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    // Liste des utilisateurs disponibles pour le chat
    public function availableUsers()
    {
        $user = Auth::user();
        $users = collect();

        if ($user->isAdmin()) {
            // Admin peut parler avec tous les laveurs et clients
            $users = User::whereIn('role', ['laveur', 'client'])
                ->where('id', '!=', $user->id)
                ->get();
        } elseif ($user->isLaveur()) {
            // Laveur peut parler avec l'admin qui lui a assigné des missions
            $adminIds = DB::table('missions')
                ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
                ->where('missions.laveur_id', $user->id)
                ->distinct()
                ->pluck('commandes.client_id');
            
            $users = User::where('role', 'admin')
                ->orWhereIn('id', $adminIds)
                ->where('id', '!=', $user->id)
                ->get();
        } elseif ($user->isClient()) {
            // Client peut parler avec les laveurs de ses commandes
            $laveurIds = DB::table('missions')
                ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
                ->where('commandes.client_id', $user->id)
                ->distinct()
                ->pluck('missions.laveur_id');
            
            $users = User::whereIn('id', $laveurIds)->get();
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

        // Laveur peut parler avec admin ou client de sa mission
        if ($user->isLaveur()) {
            if ($otherUser->isAdmin()) {
                return true;
            }
            
            if ($commandeId) {
                $mission = DB::table('missions')
                    ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
                    ->where('missions.commande_id', $commandeId)
                    ->where('missions.laveur_id', $user->id)
                    ->where('commandes.client_id', $otherUserId)
                    ->exists();
                return $mission;
            }
        }

        // Client peut parler avec le laveur de sa commande
        if ($user->isClient()) {
            if ($commandeId) {
                $mission = DB::table('missions')
                    ->join('commandes', 'missions.commande_id', '=', 'commandes.id')
                    ->where('missions.commande_id', $commandeId)
                    ->where('commandes.client_id', $user->id)
                    ->where('missions.laveur_id', $otherUserId)
                    ->exists();
                return $mission;
            }
        }

        return false;
    }
}
