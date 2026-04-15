<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Notification;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    // Créer un paiement
    public function store(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:en_ligne,fin_service'
        ]);

        $paiement = Paiement::create([
            'commande_id' => $request->commande_id,
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
            'statut' => 'en_attente'
        ]);

        return response()->json([
            'message' => 'Paiement créé',
            'paiement' => $paiement
        ], 201);
    }

    // Valider un paiement (Admin ou système de paiement)
    public function valider(Request $request, $id)
    {
        $request->validate([
            'transaction_id' => 'nullable|string'
        ]);

        $paiement = Paiement::findOrFail($id);
        $paiement->statut = 'valide';
        $paiement->date_paiement = now();
        $paiement->transaction_id = $request->transaction_id;
        $paiement->save();

        // Mettre à jour le statut de la commande
        $commande = $paiement->commande;
        $commande->statut = 'payee';
        $commande->save();

        // Générer une facture
        $numeroFacture = 'FAC-' . date('Y') . '-' . str_pad($paiement->id, 6, '0', STR_PAD_LEFT);
        
        $facture = Facture::create([
            'commande_id' => $paiement->commande_id,
            'paiement_id' => $paiement->id,
            'numero_facture' => $numeroFacture,
            'montant' => $paiement->montant,
            'date_facture' => now()
        ]);

        // Notification au client
        Notification::create([
            'user_id' => $commande->client_id,
            'titre' => 'Paiement confirmé',
            'message' => 'Votre paiement a été validé. Facture N°: ' . $numeroFacture,
            'type' => 'paiement'
        ]);

        return response()->json([
            'message' => 'Paiement validé et facture générée',
            'paiement' => $paiement,
            'facture' => $facture
        ]);
    }

    // Voir tous les paiements (Admin)
    public function index()
    {
        $paiements = Paiement::with(['commande.client'])->get();
        return response()->json($paiements);
    }

    // Voir un paiement spécifique
    public function show($id)
    {
        $paiement = Paiement::with(['commande', 'facture'])->findOrFail($id);
        return response()->json($paiement);
    }
}
