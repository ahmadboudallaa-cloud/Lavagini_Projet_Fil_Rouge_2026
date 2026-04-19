<?php

namespace App\Services;

use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Notification;

class FactureService
{
    public function genererFacture(Paiement $paiement)
    {
        // Générer un numéro de facture unique
        $annee = date('Y');
        $mois = date('m');
        $dernierNumero = Facture::whereYear('created_at', $annee)
            ->whereMonth('created_at', $mois)
            ->count();
        
        $numeroFacture = 'FAC-' . $annee . $mois . '-' . str_pad($dernierNumero + 1, 5, '0', STR_PAD_LEFT);

        // Créer la facture
        $facture = Facture::create([
            'commande_id' => $paiement->commande_id,
            'paiement_id' => $paiement->id,
            'numero_facture' => $numeroFacture,
            'montant' => $paiement->montant,
            'date_facture' => now()
        ]);

        // Notification au client
        Notification::create([
            'user_id' => $paiement->commande->client_id,
            'titre' => 'Facture générée',
            'message' => 'Votre facture ' . $numeroFacture . ' a été générée. Montant: ' . $paiement->montant . '€',
            'type' => 'paiement'
        ]);

        return $facture;
    }

    public function genererFactureAutomatique($commandeId)
    {
        $commande = \App\Models\Commande::findOrFail($commandeId);

        // Vérifier si la commande est terminée
        if ($commande->statut !== 'terminee') {
            return null;
        }

        // Vérifier si un paiement existe déjà
        if ($commande->paiement) {
            return $commande->facture;
        }

        // Créer le paiement automatiquement
        $paiement = Paiement::create([
            'commande_id' => $commandeId,
            'montant' => $commande->montant,
            'mode_paiement' => $commande->mode_paiement,
            'statut' => $commande->mode_paiement === 'fin_service' ? 'valide' : 'en_attente',
            'date_paiement' => $commande->mode_paiement === 'fin_service' ? now() : null
        ]);

        // Si paiement à la fin du service, générer la facture immédiatement
        if ($commande->mode_paiement === 'fin_service') {
            $commande->statut = 'payee';
            $commande->save();

            return $this->genererFacture($paiement);
        }

        return null;
    }
}
