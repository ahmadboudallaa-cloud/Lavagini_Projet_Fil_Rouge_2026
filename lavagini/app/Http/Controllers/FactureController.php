<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class FactureController extends Controller
{
    public function telecharger($id)
    {
        $facture = Facture::with(['commande.client', 'commande.zone', 'paiement'])->findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'client' && $facture->commande->client_id !== $user->id) {
            abort(403, 'Non autorisé');
        }
        
        // Générer le PDF
        $pdf = Pdf::loadView('pdf.facture', compact('facture'));
        
        // Télécharger le PDF
        return $pdf->download($facture->numero_facture . '.pdf');
    }
    
    public function afficher($id)
    {
        $facture = Facture::with(['commande.client', 'commande.zone', 'paiement'])->findOrFail($id);
        
        // Vérifier les permissions
        $user = Auth::user();
        if ($user->role === 'client' && $facture->commande->client_id !== $user->id) {
            abort(403, 'Non autorisé');
        }
        
        // Afficher le PDF dans le navigateur
        $pdf = Pdf::loadView('pdf.facture', compact('facture'));
        
        return $pdf->stream($facture->numero_facture . '.pdf');
    }
}
