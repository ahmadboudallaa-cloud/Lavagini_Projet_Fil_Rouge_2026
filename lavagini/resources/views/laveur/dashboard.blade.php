@extends('layouts.app')

@section('title', 'Dashboard Laveur')

@section('styles')
<style>
    .dashboard {
        padding: 2rem 0;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .dashboard-header h1 {
        margin-bottom: 0.5rem;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-card h3 {
        color: #27ae60;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #666;
    }

    .missions-list {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .missions-list h2 {
        margin-bottom: 1.5rem;
        color: #2c3e50;
    }

    .mission-item {
        border: 1px solid #eee;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }

    .mission-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .mission-info {
        margin-bottom: 1rem;
    }

    .mission-info p {
        margin: 0.3rem 0;
    }

    .mission-actions {
        display: flex;
        gap: 1rem;
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

    .badge-en-cours {
        background-color: #fdcb6e;
        color: #e17055;
    }

    .badge-terminee {
        background-color: #55efc4;
        color: #00b894;
    }

    .evaluations {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .evaluations h2 {
        margin-bottom: 1.5rem;
        color: #2c3e50;
    }

    .evaluation-item {
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
    }

    .evaluation-item:last-child {
        border-bottom: none;
    }

    .stars {
        color: #f39c12;
        font-size: 1.2rem;
    }
</style>
@endsection

@section('content')
<div class="container dashboard">
    <div class="dashboard-header">
        <h1>Bienvenue, {{ Auth::user()->name }}</h1>
        <p>Tableau de bord Laveur</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h3>{{ $totalMissions }}</h3>
            <p>Missions totales</p>
        </div>
        <div class="stat-card">
            <h3>{{ $missionsEnCours }}</h3>
            <p>Missions en cours</p>
        </div>
        <div class="stat-card">
            <h3>{{ number_format($noteMoyenne, 1) }}</h3>
            <p>Note moyenne</p>
        </div>
        <div class="stat-card">
            <h3>-</h3>
            <p>Revenus du mois</p>
        </div>
    </div>

    <div class="missions-list">
        <h2>Mes missions</h2>
        
        @forelse($missions as $mission)
        <div class="mission-item">
            <div class="mission-header">
                <strong>Mission #M{{ str_pad($mission->id, 3, '0', STR_PAD_LEFT) }}</strong>
                <span class="badge badge-{{ $mission->statut }}">{{ ucfirst(str_replace('_', ' ', $mission->statut)) }}</span>
            </div>
            <div class="mission-info">
                <p><strong>Client:</strong> {{ $mission->commande->client->name }}</p>
                <p><strong>Adresse:</strong> {{ $mission->commande->adresse_service }}</p>
                <p><strong>Véhicules:</strong> {{ $mission->commande->nombre_vehicules }}</p>
                <p><strong>Date:</strong> {{ $mission->created_at->format('d/m/Y à H:i') }}</p>
                @if($mission->temps_passe)
                <p><strong>Temps passé:</strong> {{ $mission->temps_passe }} minutes</p>
                @endif
            </div>
            <div class="mission-actions">
                @if($mission->statut === 'assignee')
                <button class="btn btn-success">Démarrer</button>
                @elseif($mission->statut === 'en_cours')
                <button class="btn btn-success">Terminer</button>
                @endif
                <button class="btn btn-primary">Voir détails</button>
            </div>
        </div>
        @empty
        <p>Aucune mission pour le moment.</p>
        @endforelse
    </div>

    <div class="evaluations">
        <h2>Mes évaluations</h2>
        
        @forelse($dernieresEvaluations as $evaluation)
        <div class="evaluation-item">
            <div class="stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $evaluation->note)
                        ★
                    @else
                        ☆
                    @endif
                @endfor
            </div>
            <p><strong>{{ $evaluation->client->name }}</strong> - {{ $evaluation->created_at->format('d/m/Y') }}</p>
            @if($evaluation->commentaire)
            <p>"{{ $evaluation->commentaire }}"</p>
            @endif
        </div>
        @empty
        <p>Aucune évaluation pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection
