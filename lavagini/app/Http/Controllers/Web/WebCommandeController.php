<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\ZoneGeographique;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebCommandeController extends Controller
{
    // Créer une nouvelle commande
    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'nullable|exists:zones_geographiques,id',
            'nombre_vehicules' => 'required|integer|min:1',
            'adresse_service' => 'required|string',
            'mode_paiement' => 'required|in:en_ligne,fin_service',
            'description' => 'nullable|string'
        ]);

        $commande = Commande::create([
            'client_id' => Auth::id(),
            'zone_id' => $request->zone_id,
            'nombre_vehicules' => $request->nombre_vehicules,
            'adresse_service' => $request->adresse_service,
            'mode_paiement' => $request->mode_paiement,
            'statut' => 'demande',
            'description' => $request->description
        ]);

        // Créer une notification
        Notification::create([
            'user_id' => Auth::id(),
            'titre' => 'Commande créée',
            'message' => 'Votre demande de service a été enregistrée avec succès',
            'type' => 'commande'
        ]);

        return redirect('/dashboard')->with('success', 'Commande créée avec succès !');
    }

    // Voir les commandes du client
    public function mesCommandes()
    {
        $commandes = Commande::where('client_id', Auth::id())
            ->with(['zone', 'mission.laveur', 'paiement', 'evaluation'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client.commandes', compact('commandes'));
    }

    // Voir une commande spécifique
    public function show($id)
    {
        $commande = Commande::with(['client', 'zone', 'mission.laveur', 'paiement', 'facture', 'evaluation'])
            ->findOrFail($id);

        // Vérifier que l'utilisateur a le droit de voir cette commande
        if (Auth::user()->role === 'client' && $commande->client_id !== Auth::id()) {
            abort(403);
        }

        return view('client.commande-detail', compact('commande'));
    }
}
