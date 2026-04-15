<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Commande;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    // Créer une évaluation (Client)
    public function store(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string'
        ]);

        // Vérifier que la commande appartient au client
        $commande = Commande::findOrFail($request->commande_id);
        
        if ($commande->client_id != Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Vérifier que la commande est terminée
        if ($commande->statut != 'terminee' && $commande->statut != 'payee') {
            return response()->json(['message' => 'La commande doit être terminée'], 400);
        }

        $evaluation = Evaluation::create([
            'commande_id' => $request->commande_id,
            'laveur_id' => $commande->mission->laveur_id,
            'client_id' => Auth::id(),
            'note' => $request->note,
            'commentaire' => $request->commentaire
        ]);

        // Notification au laveur
        Notification::create([
            'user_id' => $commande->mission->laveur_id,
            'titre' => 'Nouvelle évaluation',
            'message' => 'Vous avez reçu une note de ' . $request->note . '/5',
            'type' => 'evaluation'
        ]);

        return response()->json([
            'message' => 'Évaluation créée avec succès',
            'evaluation' => $evaluation
        ], 201);
    }

    // Voir les évaluations d'un laveur
    public function evaluationsLaveur($laveur_id)
    {
        $evaluations = Evaluation::where('laveur_id', $laveur_id)
            ->with(['client', 'commande'])
            ->get();
        
        $moyenne = $evaluations->avg('note');

        return response()->json([
            'evaluations' => $evaluations,
            'moyenne' => round($moyenne, 2)
        ]);
    }

    // Voir toutes les évaluations (Admin)
    public function index()
    {
        $evaluations = Evaluation::with(['client', 'laveur', 'commande'])->get();
        return response()->json($evaluations);
    }
}
