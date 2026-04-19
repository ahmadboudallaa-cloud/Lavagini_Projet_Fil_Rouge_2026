<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebMissionController extends Controller
{
    // Démarrer une mission
    public function demarrer($id)
    {
        $mission = Mission::findOrFail($id);
        
        if ($mission->laveur_id != Auth::id()) {
            return redirect('/laveur/dashboard')->with('error', 'Non autorisé');
        }

        $mission->statut = 'en_cours';
        $mission->date_debut = now();
        $mission->save();

        // Mettre à jour le statut de la commande
        $mission->commande->statut = 'en_cours';
        $mission->commande->save();

        // Notification au client
        Notification::create([
            'user_id' => $mission->commande->client_id,
            'titre' => 'Mission démarrée',
            'message' => 'Le laveur a commencé votre service',
            'type' => 'mission'
        ]);

        return redirect('/laveur/dashboard')->with('success', 'Mission démarrée avec succès !');
    }

    // Afficher le formulaire pour terminer une mission
    public function showTerminer($id)
    {
        $mission = Mission::with(['commande.client', 'commande.zone'])->findOrFail($id);
        
        if ($mission->laveur_id != Auth::id()) {
            abort(403);
        }

        return view('laveur.terminer-mission', compact('mission'));
    }

    // Terminer une mission
    public function terminer(Request $request, $id)
    {
        $request->validate([
            'temps_passe' => 'required|integer|min:1',
            'commentaire' => 'nullable|string'
        ]);

        $mission = Mission::findOrFail($id);
        
        if ($mission->laveur_id != Auth::id()) {
            return redirect('/laveur/dashboard')->with('error', 'Non autorisé');
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

        // Générer automatiquement la facture si paiement à la fin du service
        $factureService = new \App\Services\FactureService();
        $factureService->genererFactureAutomatique($mission->commande_id);

        return redirect('/laveur/dashboard')->with('success', 'Mission terminée avec succès !');
    }

    // Voir les détails d'une mission
    public function show($id)
    {
        $mission = Mission::with(['commande.client', 'commande.zone', 'commande.evaluation'])->findOrFail($id);
        
        if ($mission->laveur_id != Auth::id()) {
            abort(403);
        }

        return view('laveur.mission-detail', compact('mission'));
    }
}
