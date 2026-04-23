@extends('layouts.app')

@section('title', 'Détails de la mission')

@section('styles')
<style>
    .detail-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .detail-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .detail-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .detail-card h3 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #eee;
        padding-bottom: 0.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: bold;
        color: #555;
    }

    .info-value {
        color: #333;
    }

    .badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
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

    .actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>
@endsection

@section('content')
<div class="detail-container">
    <div class="detail-header">
        <h1>Mission #M{{ str_pad($mission->id, 3, '0', STR_PAD_LEFT) }}</h1>
        <p>Détails complets de votre mission</p>
    </div>

    <!-- Informations Mission -->
    <div class="detail-card">
        <h3>🚗 Informations Mission</h3>
        <div class="info-row">
            <span class="info-label">Statut :</span>
            <span class="info-value">
                <span class="badge badge-{{ $mission->statut }}">{{ ucfirst(str_replace('_', ' ', $mission->statut)) }}</span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Date d'assignation :</span>
            <span class="info-value">{{ $mission->created_at->format('d/m/Y à H:i') }}</span>
        </div>
        @if($mission->date_debut)
        <div class="info-row">
            <span class="info-label">Date de début :</span>
            <span class="info-value">{{ $mission->date_debut->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
        @if($mission->date_fin)
        <div class="info-row">
            <span class="info-label">Date de fin :</span>
            <span class="info-value">{{ $mission->date_fin->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
        @if($mission->temps_passe)
        <div class="info-row">
            <span class="info-label">Temps passé :</span>
            <span class="info-value">{{ $mission->temps_passe }} minutes</span>
        </div>
        @endif
        @if($mission->commentaire)
        <div class="info-row">
            <span class="info-label">Commentaire :</span>
            <span class="info-value">{{ $mission->commentaire }}</span>
        </div>
        @endif
    </div>

    <!-- Informations Client -->
    <div class="detail-card">
        <h3>👤 Informations Client</h3>
        <div class="info-row">
            <span class="info-label">Nom :</span>
            <span class="info-value">{{ $mission->commande->client->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email :</span>
            <span class="info-value">{{ $mission->commande->client->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Téléphone :</span>
            <span class="info-value">{{ $mission->commande->client->telephone ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Type :</span>
            <span class="info-value">{{ ucfirst($mission->commande->client->type_client ?? 'N/A') }}</span>
        </div>
    </div>

    <!-- Informations Commande -->
    <div class="detail-card">
        <h3>📋 Informations Commande</h3>
        <div class="info-row">
            <span class="info-label">Nombre de véhicules :</span>
            <span class="info-value">{{ $mission->commande->nombre_vehicules }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Adresse du service :</span>
            <span class="info-value">{{ $mission->commande->adresse_service }}</span>
        </div>
        @if($mission->commande->zone)
        <div class="info-row">
            <span class="info-label">Zone géographique :</span>
            <span class="info-value">{{ $mission->commande->zone->nom }} - {{ $mission->commande->zone->ville }}</span>
        </div>
        @endif
        @if($mission->commande->description)
        <div class="info-row">
            <span class="info-label">Description :</span>
            <span class="info-value">{{ $mission->commande->description }}</span>
        </div>
        @endif
    </div>

    <!-- Évaluation -->
    @if($mission->commande->evaluation)
    <div class="detail-card">
        <h3>⭐ Évaluation du client</h3>
        <div class="info-row">
            <span class="info-label">Note :</span>
            <span class="info-value">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $mission->commande->evaluation->note)
                        ⭐
                    @else
                        ☆
                    @endif
                @endfor
                ({{ $mission->commande->evaluation->note }}/5)
            </span>
        </div>
        @if($mission->commande->evaluation->commentaire)
        <div class="info-row">
            <span class="info-label">Commentaire :</span>
            <span class="info-value">{{ $mission->commande->evaluation->commentaire }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Date :</span>
            <span class="info-value">{{ $mission->commande->evaluation->created_at->format('d/m/Y à H:i') }}</span>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="actions">
        <a href="/laveur/dashboard" class="btn btn-primary">Retour au dashboard</a>
        
        @if($mission->statut === 'assignee')
        <form action="/laveur/missions/{{ $mission->id }}/demarrer" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success">Démarrer la mission</button>
        </form>
        @elseif($mission->statut === 'en_cours')
        <a href="/laveur/missions/{{ $mission->id }}/terminer" class="btn btn-success">Terminer la mission</a>
        @elseif($mission->statut === 'terminee' && $mission->commande->mode_paiement === 'fin_service' && $mission->commande->statut !== 'payee')
        <form action="/paiement/fin-service/{{ $mission->commande->id }}" method="POST" style="display: inline;" onsubmit="return confirm('Confirmez-vous que le client a payé ?');">
            @csrf
            <button type="submit" class="btn btn-success">💵 Marquer comme payé</button>
        </form>
        @endif
    </div>
</div>
@endsection
