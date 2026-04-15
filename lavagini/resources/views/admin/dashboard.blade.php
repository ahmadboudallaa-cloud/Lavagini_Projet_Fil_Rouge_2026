@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('styles')
<style>
    .dashboard {
        padding: 2rem 0;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
        color: #e74c3c;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #666;
    }

    .tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #eee;
    }

    .tab {
        padding: 1rem 2rem;
        cursor: pointer;
        border: none;
        background: none;
        font-size: 1rem;
        color: #666;
        transition: all 0.3s;
    }

    .tab.active {
        color: #e74c3c;
        border-bottom: 3px solid #e74c3c;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .data-table {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        background-color: #f8f9fa;
        padding: 1rem;
        text-align: left;
        font-weight: bold;
        color: #2c3e50;
    }

    table td {
        padding: 1rem;
        border-bottom: 1px solid #eee;
    }

    table tr:hover {
        background-color: #f8f9fa;
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

    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    .actions-group {
        display: flex;
        gap: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container dashboard">
    <div class="dashboard-header">
        <h1>Bienvenue, {{ Auth::user()->name }}</h1>
        <p>Tableau de bord Administrateur</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h3>{{ $totalCommandes }}</h3>
            <p>Commandes totales</p>
        </div>
        <div class="stat-card">
            <h3>{{ $commandesEnAttente }}</h3>
            <p>En attente</p>
        </div>
        <div class="stat-card">
            <h3>{{ $totalClients }}</h3>
            <p>Clients</p>
        </div>
        <div class="stat-card">
            <h3>{{ $totalLaveurs }}</h3>
            <p>Laveurs</p>
        </div>
        <div class="stat-card">
            <h3>{{ $totalZones }}</h3>
            <p>Zones</p>
        </div>
    </div>

    <div class="tabs">
        <button class="tab active" onclick="showTab('commandes')">Commandes</button>
        <button class="tab" onclick="showTab('missions')">Missions</button>
        <button class="tab" onclick="showTab('clients')">Clients</button>
        <button class="tab" onclick="showTab('laveurs')">Laveurs</button>
        <button class="tab" onclick="showTab('zones')">Zones</button>
    </div>

    <!-- Tab Commandes -->
    <div id="commandes" class="tab-content active">
        <div class="data-table">
            <h2>Gestion des commandes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Véhicules</th>
                        <th>Adresse</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                    <tr>
                        <td>#{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $commande->client->name }}</td>
                        <td>{{ $commande->nombre_vehicules }}</td>
                        <td>{{ $commande->adresse_service }}</td>
                        <td><span class="badge badge-{{ $commande->statut }}">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span></td>
                        <td>
                            <div class="actions-group">
                                @if($commande->statut === 'demande')
                                <button class="btn btn-primary btn-small">Assigner</button>
                                @endif
                                <button class="btn btn-success btn-small">Voir</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Aucune commande</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Missions -->
    <div id="missions" class="tab-content">
        <div class="data-table">
            <h2>Gestion des missions</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Commande</th>
                        <th>Laveur</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#M001</td>
                        <td>#001</td>
                        <td>Jean Laveur</td>
                        <td><span class="badge badge-assignee">Assignée</span></td>
                        <td>16/01/2026</td>
                        <td>
                            <button class="btn btn-success btn-small">Voir</button>
                        </td>
                    </tr>
                    <tr>
                        <td>#M002</td>
                        <td>#002</td>
                        <td>Marie Nettoyage</td>
                        <td><span class="badge badge-en-cours">En cours</span></td>
                        <td>15/01/2026</td>
                        <td>
                            <button class="btn btn-success btn-small">Voir</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Clients -->
    <div id="clients" class="tab-content">
        <div class="data-table">
            <h2>Gestion des clients</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Pierre Client</td>
                        <td>pierre@example.com</td>
                        <td>Particulier</td>
                        <td>0645678901</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Sophie Dupont</td>
                        <td>sophie@example.com</td>
                        <td>Particulier</td>
                        <td>0656789012</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Agence Auto Plus</td>
                        <td>agence@example.com</td>
                        <td>Agence</td>
                        <td>0667890123</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Laveurs -->
    <div id="laveurs" class="tab-content">
        <div class="data-table">
            <h2>Gestion des laveurs</h2>
            <button class="btn btn-success" style="margin-bottom: 1rem;">Ajouter un laveur</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Note moyenne</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Jean Laveur</td>
                        <td>jean@lavagini.com</td>
                        <td>0623456789</td>
                        <td>4.5 ⭐</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Marie Nettoyage</td>
                        <td>marie@lavagini.com</td>
                        <td>0634567890</td>
                        <td>4.8 ⭐</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Zones -->
    <div id="zones" class="tab-content">
        <div class="data-table">
            <h2>Gestion des zones géographiques</h2>
            <button class="btn btn-success" style="margin-bottom: 1rem;">Ajouter une zone</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Code Postal</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Paris Centre</td>
                        <td>Paris</td>
                        <td>75001</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Paris Nord</td>
                        <td>Paris</td>
                        <td>75018</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                td>Lyon Centre</td>
                        <td>Lyon</td>
                        <td>69001</td>
                        <td>
                            <div class="actions-group">
                                <button class="btn btn-primary btn-small">Modifier</button>
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showTab(tabName) {
        // Cacher tous les contenus
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.remove('active'));

        // Désactiver tous les tabs
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => tab.classList.remove('active'));

        // Activer le tab sélectionné
        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }
</script>
@endsection
