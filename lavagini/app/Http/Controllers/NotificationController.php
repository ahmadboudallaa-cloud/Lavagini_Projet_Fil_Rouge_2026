<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Voir les notifications de l'utilisateur connecté
    public function mesNotifications()
    {
        $notifications = $this->notificationsForCurrentUser()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    // Voir les notifications non lues
    public function nonLues()
    {
        $notifications = $this->notificationsForCurrentUser()
            ->where('lu', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    // Marquer une notification comme lue
    public function marquerCommeLue($id)
    {
        $notification = Notification::findOrFail($id);

        if ($notification->user_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $notification->marquerCommeLu();

        return response()->json([
            'message' => 'Notification marquée comme lue'
        ]);
    }

    // Marquer toutes les notifications comme lues
    public function marquerToutCommeLu()
    {
        $this->notificationsForCurrentUser()
            ->where('lu', false)
            ->update(['lu' => true]);

        return response()->json([
            'message' => 'Toutes les notifications sont marquées comme lues'
        ]);
    }

    // Obtenir le nombre de notifications non lues
    public function compterNonLues()
    {
        $count = $this->notificationsForCurrentUser()
            ->where('lu', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    private function notificationsForCurrentUser()
    {
        return Notification::where('user_id', Auth::id());
    }
}
