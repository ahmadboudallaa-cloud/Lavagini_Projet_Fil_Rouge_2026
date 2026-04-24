<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\Facture;
use App\Models\Notification;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;

class FactureService
{
    public function genererFacture(Paiement $paiement): Facture
    {
        return DB::transaction(function () use ($paiement) {
            $paiement = Paiement::with('commande')->lockForUpdate()->findOrFail($paiement->id);

            $factureExistante = Facture::where('paiement_id', $paiement->id)
                ->orWhere('commande_id', $paiement->commande_id)
                ->first();

            if ($factureExistante) {
                return $factureExistante;
            }

            $numeroFacture = $this->genererNumeroFacture($paiement);

            $facture = Facture::create([
                'commande_id' => $paiement->commande_id,
                'paiement_id' => $paiement->id,
                'numero_facture' => $numeroFacture,
                'montant' => $paiement->montant,
                'date_facture' => $paiement->date_paiement ?? now(),
            ]);

            Notification::create([
                'user_id' => $paiement->commande->client_id,
                'titre' => 'Facture generee',
                'message' => 'Votre facture ' . $numeroFacture . ' a ete generee. Montant: ' . $paiement->montant . ' DH',
                'type' => 'paiement',
            ]);

            return $facture;
        });
    }

    public function genererFactureAutomatique(int $commandeId): ?Facture
    {
        $commande = Commande::with(['facture', 'paiement'])->findOrFail($commandeId);

        if ($commande->statut !== 'terminee') {
            return null;
        }

        if ($commande->facture) {
            return $commande->facture;
        }

        $paiement = $commande->paiement;

        if (!$paiement) {
            $paiement = Paiement::create([
                'commande_id' => $commande->id,
                'montant' => $commande->montant,
                'mode_paiement' => $commande->mode_paiement,
                'statut' => $commande->mode_paiement === 'fin_service' ? 'valide' : 'en_attente',
                'date_paiement' => $commande->mode_paiement === 'fin_service' ? now() : null,
            ]);
        }

        if ($commande->mode_paiement === 'fin_service' && $paiement->statut === 'valide') {
            if ($commande->statut !== 'payee') {
                $commande->statut = 'payee';
                $commande->save();
            }

            return $this->genererFacture($paiement);
        }

        return null;
    }

    private function genererNumeroFacture(Paiement $paiement): string
    {
        $dateReference = $paiement->date_paiement ?? $paiement->created_at ?? now();

        return 'FAC-' . $dateReference->format('Ym') . '-' . str_pad((string) $paiement->id, 5, '0', STR_PAD_LEFT);
    }
}
