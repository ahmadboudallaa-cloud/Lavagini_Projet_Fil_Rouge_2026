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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

    .evaluation-form {
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 10px;
        margin-top: 2rem;
    }

    .stars-input {
        display: flex;
        gap: 0.5rem;
        font-size: 2rem;
        margin: 1rem 0;
    }

    .star {
        cursor: pointer;
        color: #ddd;
        transition: color 0.2s;
    }

    .star:hover,
    .star.active {
        color: #f39c12;
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

    .form-group textarea {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        min-height: 100px;
    }
</style>
@endsection

@section('content')
<div class="detail-container">
    <div class="detail-header">
        <h1>Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}</h1>
        <p>Détails complets de votre commande</p>
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
    </div>
    @endif

    <!-- Évaluation existante -->
    @if($commande->evaluation)
    <div class="detail-card">
        <h3>⭐ Votre Évaluation</h3>
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
        <div class="info-row">
            <span class="info-label">Date :</span>
            <span class="info-value">{{ $commande->evaluation->created_at->format('d/m/Y à H:i') }}</span>
        </div>
    </div>
    @endif

    <!-- Formulaire d'évaluation -->
    @if(($commande->statut === 'terminee' || $commande->statut === 'payee') && !$commande->evaluation && $commande->mission)
    <div class="detail-card">
        <h3>⭐ Évaluer le service</h3>
        <div class="evaluation-form">
            <form action="/client/evaluations/{{ $commande->id }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Note (sur 5) :</label>
                    <div class="stars-input">
                        <span class="star" data-value="1" onclick="setRating(1)">☆</span>
                        <span class="star" data-value="2" onclick="setRating(2)">☆</span>
                        <span class="star" data-value="3" onclick="setRating(3)">☆</span>
                        <span class="star" data-value="4" onclick="setRating(4)">☆</span>
                        <span class="star" data-value="5" onclick="setRating(5)">☆</span>
                    </div>
                    <input type="hidden" id="note" name="note" value="5" required>
                </div>

                <div class="form-group">
                    <label for="commentaire">Commentaire (optionnel) :</label>
                    <textarea id="commentaire" name="commentaire" placeholder="Partagez votre expérience..."></textarea>
                </div>

                <button type="submit" class="btn btn-success">Envoyer l'évaluation</button>
            </form>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="actions">
        @if($commande->statut === 'terminee' && $commande->mode_paiement === 'en_ligne')
        <form action="/paiement/stripe/{{ $commande->id }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success">💳 Payer maintenant avec Stripe</button>
        </form>
        @endif
        
        <a href="/client/commandes" class="btn btn-primary">Retour à mes commandes</a>
        <a href="/client/dashboard" class="btn btn-success">Retour au dashboard</a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentRating = 5;

    function setRating(rating) {
        currentRating = rating;
        document.getElementById('note').value = rating;
        
        const stars = document.querySelectorAll('.star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
                star.textContent = '★';
            } else {
                star.classList.remove('active');
                star.textContent = '☆';
            }
        });
    }

    // Initialiser avec 5 étoiles
    window.addEventListener('DOMContentLoaded', function() {
        setRating(5);
    });
</script>
@endsection
