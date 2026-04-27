<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Evaluation;
use App\Models\Facture;
use App\Models\Notification;
use App\Models\Tarif;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebCommandeController extends Controller
{
    // Créer une nouvelle commande
    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone_id' => 'nullable|exists:zones_geographiques,id',
            'nombre_vehicules' => 'required|integer|min:1',
            'type_service' => 'required|in:lavage_standard,lavage_complet,lavage_premium',
            'adresse_service' => 'required|string',
            'mode_paiement' => 'required|in:en_ligne,fin_service',
            'description' => 'nullable|string'
        ]);

        $user = $this->currentUser();
        $montant = $this->getMontantParTypeService(
            $validated['type_service'],
            (int) $validated['nombre_vehicules']
        );

        $commande = Commande::create([
            'client_id' => $user->id,
            'zone_id' => $validated['zone_id'] ?? null,
            'nombre_vehicules' => $validated['nombre_vehicules'],
            'type_service' => $validated['type_service'],
            'adresse_service' => $validated['adresse_service'],
            'mode_paiement' => $validated['mode_paiement'],
            'statut' => 'demande',
            'montant' => $montant,
            'description' => $validated['description'] ?? null
        ]);

        // Créer une notification pour le client
        Notification::create([
            'user_id' => $user->id,
            'titre' => 'Commande créée',
            'message' => 'Votre demande de service a été enregistrée avec succès. Montant: ' . number_format($montant, 2) . ' DH',
            'type' => 'commande'
        ]);

        // Créer une notification pour l'admin
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'titre' => 'Nouvelle commande',
                'message' => 'Une nouvelle commande a été créée par ' . $user->name . '. Montant: ' . number_format($montant, 2) . ' DH',
                'type' => 'commande'
            ]);
        }

        // Si paiement en ligne, rediriger vers Stripe
        if ($validated['mode_paiement'] === 'en_ligne') {
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
        $commande = $this->findCommandeWithRelations($id);

        $this->abortIfClientCannotAccessCommande($commande);

        return view('client.commande-detail', compact('commande'));
    }

    // Afficher la page d'évaluation
    public function showEvaluation($id)
    {
        $commande = $this->findCommandeWithRelations($id);

        $this->abortIfClientCannotAccessCommande($commande);

        return view('client.evaluation', compact('commande'));
    }

    // Créer une évaluation
    public function creerEvaluation(Request $request, $commandeId)
    {
        $validated = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string'
        ]);

        $commande = Commande::findOrFail($commandeId);

        if ($commande->client_id != Auth::id()) {
            return redirect('/client/dashboard')->with('error', 'Non autorisé');
        }

        if ($commande->statut != 'terminee' && $commande->statut != 'payee') {
            return redirect('/client/dashboard')->with('error', 'La commande doit être terminée');
        }

        if (!$commande->mission) {
            return redirect('/client/dashboard')->with('error', 'Aucune mission trouvée');
        }

        if ($commande->evaluation) {
            return redirect('/client/dashboard')->with('error', 'Vous avez déjà évalué cette commande');
        }

        Evaluation::create([
            'commande_id' => $commandeId,
            'laveur_id' => $commande->mission->laveur_id,
            'client_id' => Auth::id(),
            'note' => $validated['note'],
            'commentaire' => $validated['commentaire'] ?? null
        ]);

        // Notification au laveur
        Notification::create([
            'user_id' => $commande->mission->laveur_id,
            'titre' => 'Nouvelle évaluation',
            'message' => 'Vous avez reçu une note de ' . $validated['note'] . '/5',
            'type' => 'evaluation'
        ]);

        return redirect('/client/dashboard')->with('success', 'Évaluation créée avec succès !');
    }

    private function currentUser(): User
    {
        return Auth::user();
    }

    private function findCommandeWithRelations($id): Commande
    {
        return Commande::with(['client', 'zone', 'mission.laveur', 'paiement', 'facture', 'evaluation'])
            ->findOrFail($id);
    }

    private function abortIfClientCannotAccessCommande(Commande $commande): void
    {
        if ($this->currentUser()->role === 'client' && $commande->client_id !== Auth::id()) {
            abort(403);
        }
    }
}
