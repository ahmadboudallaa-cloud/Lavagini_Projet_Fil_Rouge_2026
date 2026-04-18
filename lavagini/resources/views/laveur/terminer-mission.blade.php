@extends('layouts.app')

@section('title', 'Terminer la mission')

@section('styles')
<style>
    .terminer-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        margin-bottom: 0.5rem;
    }

    .mission-info {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .mission-info h3 {
        color: #2c3e50;
        margin-bottom: 1rem;
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

    .form-card {
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-card h3 {
        color: #2c3e50;
        margin-bottom: 1.5rem;
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
    .form-group textarea {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }

    .form-group textarea {
        min-height: 100px;
    }

    .actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
</style>
@endsection

@section('content')
<div class="terminer-container">
    <div class="page-header">
        <h1>Terminer la mission</h1>
        <p>Mission #M{{ str_pad($mission->id, 3, '0', STR_PAD_LEFT) }}</p>
    </div>

    <!-- Informations de la mission -->
    <div class="mission-info">
        <h3>📋 Informations de la mission</h3>
        <div class="info-row">
            <span class="info-label">Client :</span>
            <span class="info-value">{{ $mission->commande->client->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Adresse :</span>
            <span class="info-value">{{ $mission->commande->adresse_service }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nombre de véhicules :</span>
            <span class="info-value">{{ $mission->commande->nombre_vehicules }}</span>
        </div>
        @if($mission->date_debut)
        <div class="info-row">
            <span class="info-label">Heure de début :</span>
            <span class="info-value">{{ $mission->date_debut->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
    </div>

    <!-- Formulaire pour terminer -->
    <div class="form-card">
        <h3>✅ Finaliser la mission</h3>
        
        <form action="/laveur/missions/{{ $mission->id }}/terminer" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="temps_passe">Temps passé (en minutes) *</label>
                <input type="number" id="temps_passe" name="temps_passe" min="1" required placeholder="Ex: 60">
            </div>

            <div class="form-group">
                <label for="commentaire">Commentaire (optionnel)</label>
                <textarea id="commentaire" name="commentaire" placeholder="Ajoutez des notes sur le service effectué..."></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-success">Terminer la mission</button>
                <a href="/laveur/dashboard" class="btn btn-primary">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
