@extends('layouts.app')

@section('title', 'Mes commandes')

@section('styles')
<style>
    .commandes-container {
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

    .page-header h1 {
        margin-bottom: 0.5rem;
    }

    .commandes-grid {
        display: grid;
        gap: 1.5rem;
    }

    .commande-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .commande-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .commande-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eee;
    }

    .commande-id {
        font-size: 1.2rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
    }

    .badge-demande {
        background-color: #ffeaa7;
        color: #d63031;
    }

    .badge-assignee {
        background-color: #74b9ff;
        color: #0984e3;
    }

    .badge-en_cours {
        background-color: #fdcb6e;
        color: #e17055;
    }

    .badge-terminee {
        background-color: #55efc4;
        color: #00b894;
    }

    .badge-payee {
        background-color: #00b894;
        color: white;
    }

    .commande-info {
        margin-bottom: 1rem;
    }

    .commande-info p {
        margin: 0.5rem 0;
        color: #555;
    }

    .commande-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .btn-small {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .empty-state h3 {
        color: #666;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="commandes-container">
    <div class="page-header">
        <h1>Mes commandes</h1>
        <p>Historique complet de vos commandes</p>
    </div>

    <div class="commandes-grid">
        @forelse($commandes as $commande)
        <div class="commande-card">
            <div class="commande-header">
                <span class="commande-id">Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}</span>
                <span class="badge badge-{{ $commande->statut }}">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span>
            </div>

            <div class="commande-info">
                <p><strong>📍 Adresse :</strong> {{ $commande->adresse_service }}</p>
                <p><strong>🚗 Véhicules :</strong> {{ $commande->nombre_vehicules }}</p>
                @if($commande->zone)
                <p><strong>🗺️ Zone :</strong> {{ $commande->zone->nom }} - {{ $commande->zone->ville }}</p>
                @endif
                <p><strong>📅 Date :</strong> {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                @if($commande->mission)
                <p><strong>👨‍🔧 Laveur :</strong> {{ $commande->mission->laveur->name }}</p>
                @endif
                @if($commande->montant)
                <p><strong>💰 Montant :</strong> {{ $commande->montant }} €</p>
                @endif
            </div>

            <div class="commande-actions">
                <a href="/client/commandes/{{ $commande->id }}" class="btn btn-primary btn-small">Voir détails</a>
                
                @if(($commande->statut === 'terminee' || $commande->statut === 'payee') && !$commande->evaluation && $commande->mission)
                <a href="/client/commandes/{{ $commande->id }}" class="btn btn-success btn-small">⭐ Évaluer</a>
                @endif

                @if($commande->evaluation)
                <span class="btn btn-small" style="background: #ddd; cursor: default;">✅ Évaluée</span>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <h3>Aucune commande</h3>
            <p>Vous n'avez pas encore passé de commande</p>
            <a href="/client/dashboard" class="btn btn-primary" style="margin-top: 1rem;">Créer une commande</a>
        </div>
        @endforelse
    </div>

    <div style="margin-top: 2rem;">
        <a href="/client/dashboard" class="btn btn-primary">Retour au dashboard</a>
    </div>
</div>
@endsection
