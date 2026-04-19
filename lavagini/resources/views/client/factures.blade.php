@extends('layouts.app')

@section('title', 'Mes factures')

@section('styles')
<style>
    .factures-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .factures-grid {
        display: grid;
        gap: 1.5rem;
    }

    .facture-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .facture-info h3 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .facture-info p {
        color: #666;
        margin: 0.3rem 0;
    }

    .facture-montant {
        font-size: 1.5rem;
        font-weight: bold;
        color: #27ae60;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="factures-container">
    <div class="page-header">
        <h1>Mes factures</h1>
        <p>Historique de vos factures</p>
    </div>

    <div class="factures-grid">
        @forelse($factures as $facture)
        <div class="facture-card">
            <div class="facture-info">
                <h3>📄 {{ $facture->numero_facture }}</h3>
                <p><strong>Commande :</strong> #{{ str_pad($facture->commande_id, 3, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Date :</strong> {{ $facture->date_facture->format('d/m/Y à H:i') }}</p>
                <p><strong>Véhicules :</strong> {{ $facture->commande->nombre_vehicules }}</p>
                <p><strong>Service :</strong> {{ ucfirst(str_replace('_', ' ', $facture->commande->type_service)) }}</p>
            </div>
            <div>
                <div class="facture-montant">{{ $facture->montant }}€</div>
                <a href="/factures/{{ $facture->id }}/telecharger" class="btn btn-primary btn-small" style="margin-top: 1rem;">Télécharger PDF</a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <h3>Aucune facture</h3>
            <p>Vous n'avez pas encore de facture</p>
            <a href="/client/dashboard" class="btn btn-primary" style="margin-top: 1rem;">Retour au dashboard</a>
        </div>
        @endforelse
    </div>

    @if($factures->count() > 0)
    <div style="margin-top: 2rem;">
        <a href="/client/dashboard" class="btn btn-primary">Retour au dashboard</a>
    </div>
    @endif
</div>
@endsection
