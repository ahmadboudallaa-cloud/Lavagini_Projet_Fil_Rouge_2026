<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    public function telecharger($id)
    {
        $facture = $this->getAuthorizedFacture($id);

        return $this->renderPdf($facture)->download($facture->numero_facture . '.pdf');
    }
    
    public function afficher($id)
    {
        $facture = $this->getAuthorizedFacture($id);

        return $this->renderPdf($facture)->stream($facture->numero_facture . '.pdf');
    }

    private function getAuthorizedFacture($id)
    {
        $facture = Facture::with(['commande.client', 'commande.zone', 'paiement'])->findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'client' && $facture->commande->client_id !== $user->id) {
            abort(403, 'Non autorisé');
        }

        return $facture;
    }

    private function renderPdf($facture)
    {
        return Pdf::loadView('pdf.facture', compact('facture'));
    }
}
