@extends('layouts.app')

@section('title', 'Dashboard Client')

@section('styles')
<style>
    .dashboard {
        padding: 2rem 0;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        color: #3498db;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #666;
    }

    .actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .action-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .action-card:hover {
        transform: translateY(-5px);
    }

    .action-card h3 {
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .commandes-list {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .commandes-list h2 {
        margin-bottom: 1.5rem;
        color: #2c3e50;
    }

    .commande-item {
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
    }

    .commande-item:last-child {
        border-bottom: none;
    }

    .commande-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
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

    .badge-en-cours {
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

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
    }

    .modal-content {
        background: white;
        max-width: 600px;
        margin: 3rem auto;
        padding: 2rem;
        border-radius: 10px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .close {
        font-size: 2rem;
        cursor: pointer;
        color: #999;
    }

    .close:hover {
        color: #333;
    }
</style>
@endsection

@section('content')
<div class="container dashboard">
    <div class="dashboard-header">
        <h1>Bienvenue, {{ Auth::user()->name }}</h1>
        <p>Tableau de bord Client</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h3>5</h3>
            <p>Commandes totales</p>
        </div>
        <div class="stat-card">
            <h3>2</h3>
            <p>En cours</p>
        </div>
        <div class="stat-card">
            <h3>3</h3>
            <p>Terminées</p>
        </div>
    </div>

    <div class="actions">
        <div class="action-card" onclick="openModal()">
            <h3>🚗 Nouvelle commande</h3>
            <p>Réserver un lavage</p>
        </div>
        <div class="action-card">
            <h3>📋 Mes commandes</h3>
            <p>Voir l'historique</p>
        </div>
        <div class="action-card">
            <h3>💳 Paiements</h3>
            <p>Gérer les paiements</p>
        </div>
        <div class="action-card">
            <h3>⭐ Évaluations</h3>
            <p>Noter les laveurs</p>
        </div>
    </div>

    <div class="commandes-list">
        <h2>Mes dernières commandes</h2>
        
        <div class="commande-item">
            <div class="commande-header">
                <strong>Commande #001</strong>
                <span class="badge badge-en-cours">En cours</span>
            </div>
            <p>2 véhicules - 15 Rue de Paris, 75001 Paris</p>
            <p><small>Date: 15/01/2026</small></p>
        </div>

        <div class="commande-item">
            <div class="commande-header">
                <strong>Commande #002</strong>
                <span class="badge badge-assignee">Assignée</span>
            </div>
            <p>1 véhicule - 45 Avenue des Champs, 75008 Paris</p>
            <p><small>Date: 14/01/2026</small></p>
        </div>

        <div class="commande-item">
            <div class="commande-header">
                <strong>Commande #003</strong>
                <span class="badge badge-terminee">Terminée</span>
            </div>
            <p>3 véhicules - 78 Boulevard Saint-Michel, 75006 Paris</p>
            <p><small>Date: 10/01/2026</small></p>
        </div>
    </div>
</div>

<!-- Modal Nouvelle Commande -->
<div id="commandeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Nouvelle commande</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        
        <form action="/commandes" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nombre_vehicules">Nombre de véhicules</label>
                <input type="number" id="nombre_vehicules" name="nombre_vehicules" min="1" required>
            </div>

            <div class="form-group">
                <label for="adresse_service">Adresse du service</label>
                <input type="text" id="adresse_service" name="adresse_service" required>
            </div>

            <div class="form-group">
                <label for="zone_id">Zone géographique</label>
                <select id="zone_id" name="zone_id">
                    <option value="">Sélectionner une zone</option>
                    <option value="1">Paris Centre</option>
                    <option value="2">Paris Nord</option>
                    <option value="3">Lyon Centre</option>
                </select>
            </div>

            <div class="form-group">
                <label for="mode_paiement">Mode de paiement</label>
                <select id="mode_paiement" name="mode_paiement" required>
                    <option value="en_ligne">Paiement en ligne</option>
                    <option value="fin_service">Paiement à la fin du service</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description (optionnel)</label>
                <input type="text" id="description" name="description">
            </div>

            <button type="submit" class="btn btn-primary btn-full">Créer la commande</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal() {
        document.getElementById('commandeModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('commandeModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('commandeModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>
@endsection
