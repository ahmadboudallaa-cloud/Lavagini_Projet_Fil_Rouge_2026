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
        $validated = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'laveur_id' => 'required|exists:users,id'
        ]);

        $mission = Mission::create([
            'commande_id' => $validated['commande_id'],
            'laveur_id' => $validated['laveur_id'],
            'statut' => 'assignee'
        ]);

        $commande = Commande::find($validated['commande_id']);
        $this->updateCommandeStatut($commande, 'assignee');

        // Notification au laveur
        Notification::create([
            'user_id' => $validated['laveur_id'],
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

        if (!$this->isCurrentUserMissionOwner($mission)) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $mission->statut = 'en_cours';
        $mission->date_debut = now();
        $mission->save();

        $this->updateCommandeStatut($mission->commande, 'en_cours');

        return response()->json([
            'message' => 'Mission démarrée',
            'mission' => $mission
        ]);
    }

    // Terminer une mission (Laveur)
    public function terminer(Request $request, $id)
    {
        $validated = $request->validate([
            'temps_passe' => 'required|integer|min:1',
            'commentaire' => 'nullable|string'
        ]);

        $mission = Mission::findOrFail($id);

        if (!$this->isCurrentUserMissionOwner($mission)) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $mission->statut = 'terminee';
        $mission->date_fin = now();
        $mission->temps_passe = $validated['temps_passe'];
        $mission->commentaire = $validated['commentaire'] ?? null;
        $mission->save();

        $this->updateCommandeStatut($mission->commande, 'terminee');

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

    private function isCurrentUserMissionOwner(Mission $mission): bool
    {
        return $mission->laveur_id == Auth::id();
    }

    private function updateCommandeStatut($commande, string $statut): void
    {
        $commande->statut = $statut;
        $commande->save();
    }
}
