<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaiementController extends Controller
{
    public function __construct()
    {
        // Ne pas initialiser Stripe si les clés ne sont pas configurées
        if (env('STRIPE_SECRET') && env('STRIPE_SECRET') !== 'sk_test_votre_cle_secrete_stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
        }
    }

    // Créer une session de paiement Stripe pour paiement en ligne
    public function creerSessionPaiement($commandeId)
    {
        try {
            $commande = Commande::findOrFail($commandeId);
            
            // Vérifier que c'est le client de la commande
            if ($commande->client_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Accès non autorisé.');
            }

            // Vérifier que la commande n'est pas déjà payée
            if ($commande->statut === 'payee') {
                return redirect()->back()->with('error', 'Cette commande est déjà payée.');
            }

            // MODE SIMULATION : Si les clés Stripe ne sont pas configurées
            if (!env('STRIPE_SECRET') || env('STRIPE_SECRET') === 'sk_test_votre_cle_secrete_stripe') {
                // Simuler un paiement réussi directement
                Paiement::create([
                    'commande_id' => $commande->id,
                    'montant' => $commande->montant,
                    'mode_paiement' => 'en_ligne',
                    'statut' => 'valide',
                    'transaction_id' => 'SIMULATION_' . time(),
                    'date_paiement' => now(),
                ]);

                // Ne pas changer le statut de la commande, elle reste en 'demande' pour assignation
                // $commande->statut = 'payee'; // SUPPRIMÉ

                Facture::create([
                    'commande_id' => $commande->id,
                    'numero_facture' => 'FAC-' . date('Y') . '-' . str_pad($commande->id, 5, '0', STR_PAD_LEFT),
                    'montant_total' => $commande->montant,
                    'date_emission' => now(),
                ]);

                return redirect('/client/dashboard')->with('success', '✅ Paiement effectué avec succès ! Votre commande sera assignée à un laveur.');
            }

            // MODE RÉEL : Créer la session Stripe
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Lavage de véhicule - Commande #' . str_pad($commande->id, 3, '0', STR_PAD_LEFT),
                            'description' => $commande->nombre_vehicules . ' véhicule(s) - ' . ucfirst(str_replace('_', ' ', $commande->type_service)),
                        ],
                        'unit_amount' => $commande->montant * 100, // Stripe utilise les centimes
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/paiement/success?session_id={CHECKOUT_SESSION_ID}&commande_id=' . $commande->id),
                'cancel_url' => url('/paiement/cancel?commande_id=' . $commande->id),
                'metadata' => [
                    'commande_id' => $commande->id,
                    'client_id' => Auth::id(),
                ],
            ]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du paiement : ' . $e->getMessage());
        }
    }

    // Page de succès après paiement Stripe
    public function paiementSuccess(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            $commandeId = $request->get('commande_id');

            if (!$sessionId || !$commandeId) {
                return redirect('/client/dashboard')->with('error', 'Session de paiement invalide.');
            }

            $commande = Commande::findOrFail($commandeId);

            // Récupérer la session Stripe
            $session = Session::retrieve($sessionId);

            // Vérifier que le paiement est réussi
            if ($session->payment_status === 'paid') {
                // Créer l'enregistrement de paiement
                $paiement = Paiement::create([
                    'commande_id' => $commande->id,
                    'montant' => $commande->montant,
                    'mode_paiement' => 'en_ligne',
                    'statut' => 'valide',
                    'transaction_id' => $session->payment_intent,
                    'date_paiement' => now(),
                ]);

                // Ne pas changer le statut de la commande, elle reste en 'demande' pour assignation
                // $commande->statut = 'payee'; // SUPPRIMÉ

                // Créer la facture
                Facture::create([
                    'commande_id' => $commande->id,
                    'numero_facture' => 'FAC-' . date('Y') . '-' . str_pad($commande->id, 5, '0', STR_PAD_LEFT),
                    'montant_total' => $commande->montant,
                    'date_emission' => now(),
                ]);

                return redirect('/client/dashboard')->with('success', 'Paiement effectué avec succès ! Votre commande sera assignée à un laveur.');
            }

            return redirect('/client/dashboard')->with('error', 'Le paiement n\'a pas été complété.');

        } catch (\Exception $e) {
            return redirect('/client/dashboard')->with('error', 'Erreur lors de la vérification du paiement : ' . $e->getMessage());
        }
    }

    // Page d'annulation de paiement
    public function paiementCancel(Request $request)
    {
        $commandeId = $request->get('commande_id');
        return redirect('/client/commandes/' . $commandeId)->with('error', 'Paiement annulé.');
    }

    // Paiement à la fin du service (par le laveur)
    public function marquerCommePayeFinService($commandeId)
    {
        try {
            $commande = Commande::findOrFail($commandeId);

            // Vérifier que c'est le laveur de la mission
            $mission = $commande->mission;
            if (!$mission || $mission->laveur_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Accès non autorisé.');
            }

            // Vérifier que la mission est terminée
            if ($mission->statut !== 'terminee') {
                return redirect()->back()->with('error', 'La mission doit être terminée avant de marquer comme payée.');
            }

            // Vérifier que la commande n'est pas déjà payée
            if ($commande->statut === 'payee') {
                return redirect()->back()->with('error', 'Cette commande est déjà payée.');
            }

            // Créer l'enregistrement de paiement
            $paiement = Paiement::create([
                'commande_id' => $commande->id,
                'montant' => $commande->montant,
                'mode_paiement' => 'fin_service',
                'statut' => 'valide',
                'date_paiement' => now(),
            ]);

            // Mettre à jour le statut de la commande
            $commande->statut = 'payee';
            $commande->save();

            // Créer la facture
            Facture::create([
                'commande_id' => $commande->id,
                'numero_facture' => 'FAC-' . date('Y') . '-' . str_pad($commande->id, 5, '0', STR_PAD_LEFT),
                'montant_total' => $commande->montant,
                'date_emission' => now(),
            ]);

            return redirect()->back()->with('success', 'Paiement enregistré avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement du paiement : ' . $e->getMessage());
        }
    }
}
