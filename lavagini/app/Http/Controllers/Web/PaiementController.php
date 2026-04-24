<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Paiement;
use App\Services\FactureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaiementController extends Controller
{
    private FactureService $factureService;

    public function __construct()
    {
        $this->factureService = new FactureService();

        if (env('STRIPE_SECRET') && env('STRIPE_SECRET') !== 'sk_test_votre_cle_secrete_stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
        }
    }

    public function creerSessionPaiement($commandeId)
    {
        try {
            $commande = Commande::findOrFail($commandeId);

            if ($commande->client_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Acces non autorise.');
            }

            if ($commande->statut === 'payee') {
                return redirect()->back()->with('error', 'Cette commande est deja payee.');
            }

            if (!env('STRIPE_SECRET') || env('STRIPE_SECRET') === 'sk_test_votre_cle_secrete_stripe') {
                $paiement = Paiement::where('commande_id', $commande->id)
                    ->where('statut', 'valide')
                    ->latest('id')
                    ->first();

                if (!$paiement) {
                    $paiement = Paiement::create([
                        'commande_id' => $commande->id,
                        'montant' => $commande->montant,
                        'mode_paiement' => 'en_ligne',
                        'statut' => 'valide',
                        'transaction_id' => 'SIMULATION_' . time(),
                        'date_paiement' => now(),
                    ]);
                }

                $this->factureService->genererFacture($paiement);

                return redirect('/client/dashboard')->with('success', 'Paiement effectue avec succes ! Votre commande sera assignee a un laveur.');
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Lavage de vehicule - Commande #' . str_pad($commande->id, 3, '0', STR_PAD_LEFT),
                            'description' => $commande->nombre_vehicules . ' vehicule(s) - ' . ucfirst(str_replace('_', ' ', $commande->type_service)),
                        ],
                        'unit_amount' => $commande->montant * 100,
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
            return redirect()->back()->with('error', 'Erreur lors de la creation du paiement : ' . $e->getMessage());
        }
    }

    public function paiementSuccess(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            $commandeId = $request->get('commande_id');

            if (!$sessionId || !$commandeId) {
                return redirect('/client/dashboard')->with('error', 'Session de paiement invalide.');
            }

            $commande = Commande::findOrFail($commandeId);
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $paiement = Paiement::where('commande_id', $commande->id)
                    ->where('transaction_id', $session->payment_intent)
                    ->first();

                if (!$paiement) {
                    $paiement = Paiement::create([
                        'commande_id' => $commande->id,
                        'montant' => $commande->montant,
                        'mode_paiement' => 'en_ligne',
                        'statut' => 'valide',
                        'transaction_id' => $session->payment_intent,
                        'date_paiement' => now(),
                    ]);
                }

                $this->factureService->genererFacture($paiement);

                return redirect('/client/dashboard')->with('success', 'Paiement effectue avec succes ! Votre commande sera assignee a un laveur.');
            }

            return redirect('/client/dashboard')->with('error', 'Le paiement n a pas ete complete.');
        } catch (\Exception $e) {
            return redirect('/client/dashboard')->with('error', 'Erreur lors de la verification du paiement : ' . $e->getMessage());
        }
    }

    public function paiementCancel(Request $request)
    {
        $commandeId = $request->get('commande_id');

        return redirect('/client/commandes/' . $commandeId)->with('error', 'Paiement annule.');
    }

    public function marquerCommePayeFinService($commandeId)
    {
        try {
            $commande = Commande::findOrFail($commandeId);

            $mission = $commande->mission;
            if (!$mission || $mission->laveur_id !== Auth::id()) {
                return redirect()->back()->with('error', 'Acces non autorise.');
            }

            if ($mission->statut !== 'terminee') {
                return redirect()->back()->with('error', 'La mission doit etre terminee avant de marquer comme payee.');
            }

            if ($commande->statut === 'payee' && $commande->facture) {
                return redirect()->back()->with('error', 'Cette commande est deja payee.');
            }

            $paiement = Paiement::where('commande_id', $commande->id)
                ->where('mode_paiement', 'fin_service')
                ->where('statut', 'valide')
                ->latest('id')
                ->first();

            if (!$paiement) {
                $paiement = Paiement::create([
                    'commande_id' => $commande->id,
                    'montant' => $commande->montant,
                    'mode_paiement' => 'fin_service',
                    'statut' => 'valide',
                    'date_paiement' => now(),
                ]);
            }

            $commande->statut = 'payee';
            $commande->save();

            $this->factureService->genererFacture($paiement);

            return redirect()->back()->with('success', 'Paiement enregistre avec succes !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l enregistrement du paiement : ' . $e->getMessage());
        }
    }
}
