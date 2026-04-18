@extends('layouts.app')

@section('title', 'Détails de la commande')

@section('styles')
<style>
    .detail-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .detail-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

    .actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
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

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #555;
        font-weight: bold;
    }

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
<div class="detail-container">
    <div class="detail-header">
        <h1>Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}</h1>
        <p>Détails complets de la commande</p>
    </div>

    <!-- Informations Client -->
    <div class="detail-card">
        <h3>👤 Informations Client</h3>
        <div class="info-row">
            <span class="info-label">Nom :</span>
            <span class="info-value">{{ $commande->client->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email :</span>
            <span class="info-value">{{ $commande->client->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Téléphone :</span>
            <span class="info-value">{{ $commande->client->telephone ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Type :</span>
            <span class="info-value">{{ ucfirst($commande->client->type_client ?? 'N/A') }}</span>
        </div>
    </div>

    <!-- Informations Commande -->
    <div class="detail-card">
        <h3>📋 Informations Commande</h3>
        <div class="info-row">
            <span class="info-label">Statut :</span>
            <span class="info-value">
                <span class="badge badge-{{ $commande->statut }}">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Nombre de véhicules :</span>
            <span class="info-value">{{ $commande->nombre_vehicules }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Adresse du service :</span>
            <span class="info-value">{{ $commande->adresse_service }}</span>
        </div>
        @if($commande->zone)
        <div class="info-row">
            <span class="info-label">Zone géographique :</span>
            <span class="info-value">{{ $commande->zone->nom }} - {{ $commande->zone->ville }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Mode de paiement :</span>
            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $commande->mode_paiement)) }}</span>
        </div>
        @if($commande->montant)
        <div class="info-row">
            <span class="info-label">Montant :</span>
            <span class="info-value">{{ $commande->montant }} €</span>
        </div>
        @endif
        @if($commande->description)
        <div class="info-row">
            <span class="info-label">Description :</span>
            <span class="info-value">{{ $commande->description }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Date de création :</span>
            <span class="info-value">{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
        </div>
    </div>

    <!-- Informations Mission -->
    @if($commande->mission)
    <div class="detail-card">
        <h3>🚗 Informations Mission</h3>
        <div class="info-row">
            <span class="info-label">Laveur assigné :</span>
            <span class="info-value">{{ $commande->mission->laveur->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email laveur :</span>
            <span class="info-value">{{ $commande->mission->laveur->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Téléphone laveur :</span>
            <span class="info-value">{{ $commande->mission->laveur->telephone ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Statut mission :</span>
            <span class="info-value">
                <span class="badge badge-{{ $commande->mission->statut }}">{{ ucfirst(str_replace('_', ' ', $commande->mission->statut)) }}</span>
            </span>
        </div>
        @if($commande->mission->date_debut)
        <div class="info-row">
            <span class="info-label">Date début :</span>
            <span class="info-value">{{ $commande->mission->date_debut->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
        @if($commande->mission->date_fin)
        <div class="info-row">
            <span class="info-label">Date fin :</span>
            <span class="info-value">{{ $commande->mission->date_fin->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
        @if($commande->mission->temps_passe)
        <div class="info-row">
            <span class="info-label">Temps passé :</span>
            <span class="info-value">{{ $commande->mission->temps_passe }} minutes</span>
        </div>
        @endif
        @if($commande->mission->commentaire)
        <div class="info-row">
            <span class="info-label">Commentaire :</span>
            <span class="info-value">{{ $commande->mission->commentaire }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Informations Paiement -->
    @if($commande->paiement)
    <div class="detail-card">
        <h3>💳 Informations Paiement</h3>
        <div class="info-row">
            <span class="info-label">Montant :</span>
            <span class="info-value">{{ $commande->paiement->montant }} €</span>
        </div>
        <div class="info-row">
            <span class="info-label">Statut :</span>
            <span class="info-value">{{ ucfirst($commande->paiement->statut) }}</span>
        </div>
        @if($commande->paiement->transaction_id)
        <div class="info-row">
            <span class="info-label">ID Transaction :</span>
            <span class="info-value">{{ $commande->paiement->transaction_id }}</span>
        </div>
        @endif
        @if($commande->paiement->date_paiement)
        <div class="info-row">
            <span class="info-label">Date paiement :</span>
            <span class="info-value">{{ $commande->paiement->date_paiement->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Informations Évaluation -->
    @if($commande->evaluation)
    <div class="detail-card">
        <h3>⭐ Évaluation</h3>
        <div class="info-row">
            <span class="info-label">Note :</span>
            <span class="info-value">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $commande->evaluation->note)
                        ⭐
                    @else
                        ☆
                    @endif
                @endfor
                ({{ $commande->evaluation->note }}/5)
            </span>
        </div>
        @if($commande->evaluation->commentaire)
        <div class="info-row">
            <span class="info-label">Commentaire :</span>
            <span class="info-value">{{ $commande->evaluation->commentaire }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Actions -->
    <div class="actions">
        <a href="/admin/dashboard" class="btn btn-primary">Retour au dashboard</a>
        
        @if($commande->statut === 'demande')
        <button class="btn btn-success" onclick="openAssignerModal()">Assigner un laveur</button>
        @endif
    </div>
</div>

<!-- Modal Assigner Mission -->
@if($commande->statut === 'demande')
<div id="assignerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Assigner un laveur</h2>
            <span class="close" onclick="closeAssignerModal()">&times;</span>
        </div>
        
        <form action="/admin/missions/assigner" method="POST">
            @csrf
            
            <input type="hidden" name="commande_id" value="{{ $commande->id }}">

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
@endif
@endsection

@section('scripts')
<script>
    function openAssignerModal() {
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
