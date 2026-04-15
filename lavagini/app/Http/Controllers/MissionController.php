<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\Commande;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MissionController extends Controller
{
    // Assigner une mission à un laveur (Admin)
    public function assigner(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'laveur_id' => 'required|exists:users,id'
        ]);

        $mission = Mission::create([
            'commande_id' => $request->commande_id,
            'laveur_id' => $request->laveur_id,
            'statut' => 'assignee'
        ]);

        // Mettre à jour le statut de la commande
        $commande = Commande::find($request->commande_id);
        $commande->statut = 'assignee';
        $commande->save();

        // Notification au laveur
        Notification::create([
            'user_id' => $request->laveur_id,
            'titre' => 'Nouvelle mission',
            'message' => 'Une nouvelle mission vous a été assignée',
            'type' => 'mission'
        ]);

        // Notification au client
        Notification::create([
            'user_id' => $commande->client_id,
            'titre' => 'Mission assignée',
            'message' => 'Un laveur a été assigné à votre commande',
            'type' => 'mission'
        ]);

        return response()->json([
            'message' => 'Mission assignée avec succès',
            'mission' => $mission
        ], 201);
    }

    // Voir les missions d'un laveur
    public function mesMissions()
    {
        $missions = Mission::where('laveur_id', Auth::id())
            ->with(['commande.client', 'commande.zone'])
            ->get();
        return response()->json($missions);
    }

    // Démarrer une mission (Laveur)
    public function demarrer($id)
    {
        $mission = Mission::findOrFail($id);
        
        if ($mission->laveur_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $mission->statut = 'en_cours';
        $mission->date_debut = now();
        $mission->save();

        // Mettre à jour le statut de la commande
        $mission->commande->statut = 'en_cours';
        $mission->commande->save();

        return response()->json([
            'message' => 'Mission démarrée',
            'mission' => $mission
        ]);
    }

    // Terminer une mission (Laveur)
    public function terminer(Request $request, $id)
    {
        $request->validate([
            'temps_passe' => 'required|integer|min:1',
            'commentaire' => 'nullable|string'
        ]);

        $mission = Mission::findOrFail($id);
        
        if ($mission->laveur_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $mission->statut = 'terminee';
        $mission->date_fin = now();
        $mission->temps_passe = $request->temps_passe;
        $mission->commentaire = $request->commentaire;
        $mission->save();

        // Mettre à jour le statut de la commande
        $mission->commande->statut = 'terminee';
        $mission->commande->save();

        // Notification au client
        Notification::create([
            'user_id' => $mission->commande->client_id,
            'titre' => 'Service terminé',
            'message' => 'Votre service de lavage est terminé',
            'type' => 'mission'
        ]);

        return response()->json([
            'message' => 'Mission terminée',
            'mission' => $mission
        ]);
    }

    // Voir toutes les missions (Admin)
    public function index()
    {
        $missions = Mission::with(['commande.client', 'laveur'])->get();
        return response()->json($missions);
    }
}
