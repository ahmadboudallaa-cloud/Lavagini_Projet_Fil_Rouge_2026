<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommandeController extends Controller
{
    // Créer une nouvelle commande (Client)
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

        // Créer une notification pour le client
        Notification::create([
            'user_id' => Auth::id(),
            'titre' => 'Commande créée',
            'message' => 'Votre demande de service a été enregistrée',
            'type' => 'commande'
        ]);

        return response()->json([
            'message' => 'Commande créée avec succès',
            'commande' => $commande
        ], 201);
    }

    // Voir toutes les commandes (Admin)
    public function index()
    {
        $commandes = Commande::with(['client', 'zone', 'mission.laveur'])->get();
        return response()->json($commandes);
    }

    // Voir les commandes d'un client
    public function mesCommandes()
    {
        $commandes = Commande::where('client_id', Auth::id())
            ->with(['zone', 'mission.laveur', 'paiement', 'evaluation'])
            ->get();
        return response()->json($commandes);
    }

    // Voir une commande spécifique
    public function show($id)
    {
        $commande = Commande::with(['client', 'zone', 'mission.laveur', 'paiement', 'facture', 'evaluation'])
            ->findOrFail($id);
        return response()->json($commande);
    }

    // Mettre à jour le statut d'une commande (Admin)
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:demande,assignee,en_cours,terminee,payee'
        ]);

        $commande = Commande::findOrFail($id);
        $commande->statut = $request->statut;
        $commande->save();

        // Notification au client
        Notification::create([
            'user_id' => $commande->client_id,
            'titre' => 'Statut de commande mis à jour',
            'message' => 'Votre commande est maintenant : ' . $request->statut,
            'type' => 'commande'
        ]);

        return response()->json([
            'message' => 'Statut mis à jour',
            'commande' => $commande
        ]);
    }
}
