<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Notification;
use App\Models\Tarif;
use App\Models\ZoneGeographique;
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
            'type_service' => 'required|in:lavage_standard,lavage_complet,lavage_premium',
            'adresse_service' => 'required|string',
            'mode_paiement' => 'required|in:en_ligne,fin_service',
            'description' => 'nullable|string'
        ]);

        $montant = $this->getMontantParTypeService($request->type_service, (int) $request->nombre_vehicules);

        $commande = Commande::create([
            'client_id' => Auth::id(),
            'zone_id' => $request->zone_id,
            'nombre_vehicules' => $request->nombre_vehicules,
            'type_service' => $request->type_service,
            'adresse_service' => $request->adresse_service,
            'mode_paiement' => $request->mode_paiement,
            'statut' => 'demande',
            'montant' => $montant,
            'description' => $request->description
        ]);

        // Créer une notification pour le client
        Notification::create([
            'user_id' => Auth::id(),
            'titre' => 'Commande créée',
            'message' => 'Votre demande de service a été enregistrée avec succès. Montant: ' . number_format($montant, 2) . ' DH',
            'type' => 'commande'
        ]);

        // Créer une notification pour l'admin
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'titre' => 'Nouvelle commande',
                'message' => 'Une nouvelle commande a été créée par ' . Auth::user()->name . '. Montant: ' . number_format($montant, 2) . ' DH',
                'type' => 'commande'
            ]);
        }

        // Si paiement en ligne, rediriger vers Stripe
        if ($request->mode_paiement === 'en_ligne') {
            return redirect('/paiement/stripe/' . $commande->id);
        }

        return redirect('/dashboard')->with('success', 'Commande créée avec succès ! Montant: ' . number_format($montant, 2) . ' DH');
    }

    private function getMontantParTypeService(string $typeService, int $nombreVehicules): float
    {
        $tarifsParDefaut = [
            'lavage_standard' => 100,
            'lavage_complet' => 150,
            'lavage_premium' => 250,
        ];

        $tarif = Tarif::where('type_service', $typeService)->first();
        $prixUnitaire = $tarif ? (float) $tarif->prix_unitaire : ($tarifsParDefaut[$typeService] ?? 0);

        return $prixUnitaire * $nombreVehicules;
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

    // Voir les factures du client
    public function mesFactures()
    {
        $factures = Facture::whereHas('commande', function ($query) {
            $query->where('client_id', Auth::id());
        })
        ->with(['commande', 'paiement'])
        ->orderBy('created_at', 'desc')
        ->get();

        return view('client.factures', compact('factures'));
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

    // Afficher la page d'évaluation
    public function showEvaluation($id)
    {
        $commande = Commande::with(['client', 'zone', 'mission.laveur', 'paiement', 'facture', 'evaluation'])
            ->findOrFail($id);

        // Vérifier que l'utilisateur a le droit d'accéder à cette commande
        if (Auth::user()->role === 'client' && $commande->client_id !== Auth::id()) {
            abort(403);
        }

        return view('client.evaluation', compact('commande'));
    }

    // Créer une évaluation
    public function creerEvaluation(Request $request, $commandeId)
    {
        $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string'
        ]);

        $commande = Commande::findOrFail($commandeId);
        
        // Vérifier que la commande appartient au client
        if ($commande->client_id != Auth::id()) {
            return redirect('/client/dashboard')->with('error', 'Non autorisé');
        }

        // Vérifier que la commande est terminée
        if ($commande->statut != 'terminee' && $commande->statut != 'payee') {
            return redirect('/client/dashboard')->with('error', 'La commande doit être terminée');
        }

        // Vérifier qu'il y a une mission
        if (!$commande->mission) {
            return redirect('/client/dashboard')->with('error', 'Aucune mission trouvée');
        }

        // Vérifier si une évaluation existe déjà
        if ($commande->evaluation) {
            return redirect('/client/dashboard')->with('error', 'Vous avez déjà évalué cette commande');
        }

        $evaluation = \App\Models\Evaluation::create([
            'commande_id' => $commandeId,
            'laveur_id' => $commande->mission->laveur_id,
            'client_id' => Auth::id(),
            'note' => $request->note,
            'commentaire' => $request->commentaire
        ]);

        // Notification au laveur
        \App\Models\Notification::create([
            'user_id' => $commande->mission->laveur_id,
            'titre' => 'Nouvelle évaluation',
            'message' => 'Vous avez reçu une note de ' . $request->note . '/5',
            'type' => 'evaluation'
        ]);

        return redirect('/client/dashboard')->with('success', 'Évaluation créée avec succès !');
    }
}
