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
            <h3>12</h3>
            <p>Missions totales</p>
        </div>
        <div class="stat-card">
            <h3>3</h3>
            <p>Missions en cours</p>
        </div>
        <div class="stat-card">
            <h3>4.5</h3>
            <p>Note moyenne</p>
        </div>
        <div class="stat-card">
            <h3>850€</h3>
            <p>Revenus du mois</p>
        </div>
    </div>

    <div class="missions-list">
        <h2>Mes missions</h2>
        
        <div class="mission-item">
            <div class="mission-header">
                <strong>Mission #M001</strong>
                <span class="badge badge-assignee">Assignée</span>
            </div>
            <div class="mission-info">
                <p><strong>Client:</strong> Pierre Client</p>
                <p><strong>Adresse:</strong> 12 Rue des Clients, 75001 Paris</p>
                <p><strong>Véhicules:</strong> 2</p>
                <p><strong>Date:</strong> 16/01/2026 à 14h00</p>
            </div>
            <div class="mission-actions">
                <button class="btn btn-success">Démarrer</button>
                <button class="btn btn-primary">Voir détails</button>
            </div>
        </div>

        <div class="mission-item">
            <div class="mission-header">
                <strong>Mission #M002</strong>
                <span class="badge badge-en-cours">En cours</span>
            </div>
            <div class="mission-info">
                <p><strong>Client:</strong> Sophie Dupont</p>
                <p><strong>Adresse:</strong> 34 Boulevard Client, 13001 Marseille</p>
                <p><strong>Véhicules:</strong> 1</p>
                <p><strong>Date:</strong> 15/01/2026 à 10h00</p>
            </div>
            <div class="mission-actions">
                <button class="btn btn-success">Terminer</button>
                <button class="btn btn-primary">Voir détails</button>
            </div>
        </div>

        <div class="mission-item">
            <div class="mission-header">
                <strong>Mission #M003</strong>
                <span class="badge badge-terminee">Terminée</span>
            </div>
            <div class="mission-info">
                <p><strong>Client:</strong> Agence Auto Plus</p>
                <p><strong>Adresse:</strong> 56 Avenue des Agences, 69001 Lyon</p>
                <p><strong>Véhicules:</strong> 5</p>
                <p><strong>Date:</strong> 14/01/2026</p>
                <p><strong>Temps passé:</strong> 180 minutes</p>
            </div>
            <div class="mission-actions">
                <button class="btn btn-primary">Voir détails</button>
            </div>
        </div>
    </div>

    <div class="evaluations">
        <h2>Mes évaluations</h2>
        
        <div class="evaluation-item">
            <div class="stars">★★★★★</div>
            <p><strong>Pierre Client</strong> - 15/01/2026</p>
            <p>"Excellent travail, très professionnel !"</p>
        </div>

        <div class="evaluation-item">
            <div class="stars">★★★★☆</div>
            <p><strong>Sophie Dupont</strong> - 12/01/2026</p>
            <p>"Bon service, rapide et efficace."</p>
        </div>

        <div class="evaluation-item">
            <div class="stars">★★★★★</div>
            <p><strong>Agence Auto Plus</strong> - 10/01/2026</p>
            <p>"Parfait ! Nous recommandons."</p>
        </div>
    </div>
</div>
@endsection
