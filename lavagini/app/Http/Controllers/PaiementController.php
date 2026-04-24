<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Services\FactureService;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|in:en_ligne,fin_service',
        ]);

        $paiement = Paiement::create([
            'commande_id' => $request->commande_id,
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
            'statut' => 'en_attente',
        ]);

        return response()->json([
            'message' => 'Paiement cree',
            'paiement' => $paiement,
        ], 201);
    }

    public function valider(Request $request, $id)
    {
        $request->validate([
            'transaction_id' => 'nullable|string',
        ]);

        $paiement = Paiement::findOrFail($id);
        $paiement->statut = 'valide';
        $paiement->date_paiement = now();
        $paiement->transaction_id = $request->transaction_id;
        $paiement->save();

        $commande = $paiement->commande;
        $commande->statut = 'payee';
        $commande->save();

        $facture = (new FactureService())->genererFacture($paiement);

        return response()->json([
            'message' => 'Paiement valide et facture generee',
            'paiement' => $paiement,
            'facture' => $facture,
        ]);
    }

    public function index()
    {
        $paiements = Paiement::with(['commande.client'])->get();

        return response()->json($paiements);
    }

    public function show($id)
    {
        $paiement = Paiement::with(['commande', 'facture'])->findOrFail($id);

        return response()->json($paiement);
    }
}
