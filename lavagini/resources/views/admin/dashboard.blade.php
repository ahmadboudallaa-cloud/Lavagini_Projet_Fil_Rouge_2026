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

    .btn-small {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    .actions-group {
        display: flex;
        gap: 0.5rem;
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
        overflow-y: auto;
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

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
        font-weight: bold;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }

    .btn-full {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
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
                                <button class="btn btn-primary btn-small" onclick="openAssignerModal({{ $commande->id }})">Assigner</button>
                                @endif
                                <a href="/admin/commandes/{{ $commande->id }}" class="btn btn-success btn-small">Voir</a>
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($missions as $mission)
                    <tr>
                        <td>#M{{ str_pad($mission->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>#{{ str_pad($mission->commande_id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $mission->laveur->name }}</td>
                        <td><span class="badge badge-{{ $mission->statut }}">{{ ucfirst(str_replace('_', ' ', $mission->statut)) }}</span></td>
                        <td>{{ $mission->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Aucune mission</td>
                    </tr>
                    @endforelse
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
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ ucfirst($client->type_client ?? 'N/A') }}</td>
                        <td>{{ $client->telephone ?? 'N/A' }}</td>
                        <td>
                            <div class="actions-group">
                                <form action="/admin/clients/{{ $client->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center;">Aucun client</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Laveurs -->
    <div id="laveurs" class="tab-content">
        <div class="data-table">
            <h2>Gestion des laveurs</h2>
            <button class="btn btn-success" style="margin-bottom: 1rem;" onclick="openLaveurModal()">Ajouter un laveur</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laveurs as $laveur)
                    <tr>
                        <td>{{ $laveur->id }}</td>
                        <td>{{ $laveur->name }}</td>
                        <td>{{ $laveur->email }}</td>
                        <td>{{ $laveur->telephone ?? 'N/A' }}</td>
                        <td>
                            <div class="actions-group">
                                <form action="/admin/laveurs/{{ $laveur->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Aucun laveur</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Zones -->
    <div id="zones" class="tab-content">
        <div class="data-table">
            <h2>Gestion des zones géographiques</h2>
            <button class="btn btn-success" style="margin-bottom: 1rem;" onclick="openZoneModal()">Ajouter une zone</button>
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
                    @forelse($zones as $zone)
                    <tr>
                        <td>{{ $zone->id }}</td>
                        <td>{{ $zone->nom }}</td>
                        <td>{{ $zone->ville }}</td>
                        <td>{{ $zone->code_postal }}</td>
                        <td>
                            <div class="actions-group">
                                <form action="/admin/zones/{{ $zone->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Aucune zone</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Ajouter Laveur -->
<div id="laveurModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ajouter un laveur</h2>
            <span class="close" onclick="closeLaveurModal()">&times;</span>
        </div>
        
        <form action="/admin/laveurs" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" id="adresse" name="adresse">
            </div>

            <button type="submit" class="btn btn-success btn-full">Créer le laveur</button>
        </form>
    </div>
</div>

<!-- Modal Ajouter Zone -->
<div id="zoneModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ajouter une zone</h2>
            <span class="close" onclick="closeZoneModal()">&times;</span>
        </div>
        
        <form action="/admin/zones" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nom">Nom de la zone</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" id="ville" name="ville" required>
            </div>

            <div class="form-group">
                <label for="code_postal">Code postal</label>
                <input type="text" id="code_postal" name="code_postal" required>
            </div>

            <button type="submit" class="btn btn-success btn-full">Créer la zone</button>
        </form>
    </div>
</div>

<!-- Modal Assigner Mission -->
<div id="assignerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Assigner un laveur</h2>
            <span class="close" onclick="closeAssignerModal()">&times;</span>
        </div>
        
        <form action="/admin/missions/assigner" method="POST">
            @csrf
            
            <input type="hidden" id="commande_id" name="commande_id">

            <div class="form-group">
                <label for="laveur_id">Sélectionner un laveur</label>
                <select id="laveur_id" name="laveur_id" required>
                    <option value="">Choisir un laveur</option>
                    @foreach($laveurs as $laveur)
                    <option value="{{ $laveur->id }}">{{ $laveur->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-full">Assigner la mission</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showTab(tabName) {
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.remove('active'));

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => tab.classList.remove('active'));

        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }

    function openLaveurModal() {
        document.getElementById('laveurModal').style.display = 'block';
    }

    function closeLaveurModal() {
        document.getElementById('laveurModal').style.display = 'none';
    }

    function openZoneModal() {
        document.getElementById('zoneModal').style.display = 'block';
    }

    function closeZoneModal() {
        document.getElementById('zoneModal').style.display = 'none';
    }

    function openAssignerModal(commandeId) {
        document.getElementById('commande_id').value = commandeId;
        document.getElementById('assignerModal').style.display = 'block';
    }

    function closeAssignerModal() {
        document.getElementById('assignerModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
