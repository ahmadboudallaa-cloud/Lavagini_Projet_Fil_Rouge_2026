@extends('layouts.client')

@section('title', 'Évaluation de la commande')
@section('page-title', 'Évaluation')

@section('styles')
<style>
    .evaluation-page {
        display: grid;
        gap: 1.5rem;
    }

    .evaluation-hero {
        background: linear-gradient(135deg, rgba(0, 194, 255, 0.16), rgba(0, 194, 255, 0.04));
        border: 1px solid rgba(0, 194, 255, 0.3);
        border-radius: 30px;
        padding: 2rem;
    }

    .evaluation-meta-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .evaluation-meta-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 1rem 1.2rem;
    }

    .evaluation-meta-label {
        color: #9ca3af;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 0.35rem;
    }

    .evaluation-meta-value {
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
    }

    .evaluation-panel {
        background: #1f1f1f;
        border: 1px solid #333333;
        border-radius: 30px;
        padding: 2rem;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
    }

    .evaluation-panel__title {
        color: #00c2ff;
        font-size: 1.65rem;
        font-weight: 800;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .evaluation-panel__subtitle {
        color: #cfcfcf;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .evaluation-summary {
        background: #2b2b2b;
        border: 1px solid #3b3b3b;
        border-radius: 24px;
        padding: 1.5rem;
    }

    .evaluation-summary__row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .evaluation-summary__row:last-child {
        border-bottom: none;
    }

    .evaluation-summary__label {
        color: #9ca3af;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .evaluation-summary__value {
        color: #ffffff;
        font-weight: 700;
        text-align: right;
    }

    .evaluation-form {
        background: #242424;
        border: 1px solid #3a3a3a;
        border-radius: 24px;
        padding: 1.75rem;
    }

    .evaluation-label {
        color: #cfcfcf;
        font-weight: 700;
        margin-bottom: 0.75rem;
        display: block;
    }

    .stars-input {
        display: flex;
        justify-content: center;
        gap: 0.55rem;
        font-size: 2.2rem;
        margin: 0.75rem 0 0.5rem;
    }

    .star {
        cursor: pointer;
        color: #4a4a4a;
        transition: color 0.2s ease, transform 0.2s ease;
        user-select: none;
        line-height: 1;
    }

    .star:hover {
        transform: translateY(-2px) scale(1.08);
    }

    .rating-text {
        text-align: center;
        color: #00c2ff;
        font-weight: 800;
        margin-bottom: 1.1rem;
    }

    .evaluation-textarea {
        width: 100%;
        min-height: 140px;
        background: #161616;
        border: 1px solid #4a4a4a;
        border-radius: 14px;
        padding: 1rem 1.05rem;
        color: #ffffff;
        resize: vertical;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .evaluation-textarea:focus {
        outline: none;
        border-color: #00c2ff;
        box-shadow: 0 0 0 3px rgba(0, 194, 255, 0.12);
    }

    .evaluation-submit {
        width: 100%;
        margin-top: 1.2rem;
        background: #00c2ff;
        color: #000000;
        border: none;
        border-radius: 999px;
        padding: 0.95rem 1.25rem;
        font-weight: 800;
        cursor: pointer;
        transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }

    .evaluation-submit:hover {
        background: #00addf;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0, 194, 255, 0.24);
    }

    .evaluation-alert {
        background: rgba(0, 194, 255, 0.08);
        border: 1px solid rgba(0, 194, 255, 0.22);
        border-radius: 24px;
        padding: 1.5rem;
        color: #e5e7eb;
    }

    .evaluation-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .evaluation-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.9rem 1.35rem;
        border-radius: 999px;
        font-weight: 800;
        transition: transform 0.2s ease, background 0.2s ease;
    }

    .evaluation-link:hover {
        transform: translateY(-1px);
    }

    .evaluation-link--primary {
        background: #00c2ff;
        color: #000000;
    }

    .evaluation-link--primary:hover {
        background: #00addf;
    }

    .evaluation-link--secondary {
        background: #ffffff;
        color: #000000;
    }

    .evaluation-link--secondary:hover {
        background: #e5e7eb;
    }

    @media (max-width: 1024px) {
        .evaluation-meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .evaluation-hero,
        .evaluation-panel {
            padding: 1.25rem;
            border-radius: 22px;
        }

        .evaluation-meta-grid {
            grid-template-columns: 1fr;
        }

        .evaluation-panel__title {
            font-size: 1.3rem;
        }

        .evaluation-form {
            padding: 1.25rem;
            border-radius: 18px;
        }

        .stars-input {
            font-size: 2rem;
        }

        .evaluation-actions {
            flex-direction: column;
        }

        .evaluation-link {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
@php
    $canEvaluate = (($commande->statut === 'terminee' || $commande->statut === 'payee') && !$commande->evaluation && $commande->mission);
    $initialRating = old('note', 5);
@endphp

<div class="evaluation-page">
    <div class="evaluation-hero bg-dark-card rounded-[30px] shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-[0.2em] mb-2">Évaluation de la commande</p>
                <h1 class="text-4xl font-extrabold text-white">
                    Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-gray-300 mt-3 max-w-2xl">
                    Donnez votre avis sur la prestation pour aider à améliorer la qualité du service.
                </p>
            </div>

            <div class="bg-cyan-custom/10 border border-cyan-custom/25 px-4 py-2 rounded-full text-cyan-custom font-bold self-start">
                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
            </div>
        </div>

        <div class="evaluation-meta-grid">
            <div class="evaluation-meta-card">
                <div class="evaluation-meta-label">Client</div>
                <div class="evaluation-meta-value">{{ $commande->client->name }}</div>
            </div>
            <div class="evaluation-meta-card">
                <div class="evaluation-meta-label">Zone</div>
                <div class="evaluation-meta-value">{{ $commande->zone ? $commande->zone->nom : 'Non définie' }}</div>
            </div>
            <div class="evaluation-meta-card">
                <div class="evaluation-meta-label">Laveur</div>
                <div class="evaluation-meta-value">
                    {{ $commande->mission && $commande->mission->laveur ? $commande->mission->laveur->name : 'Non assigné' }}
                </div>
            </div>
            <div class="evaluation-meta-card">
                <div class="evaluation-meta-label">Montant</div>
                <div class="evaluation-meta-value">{{ $commande->montant ? $commande->montant . ' DH' : 'N/A' }}</div>
            </div>
        </div>
    </div>

    @if($commande->evaluation)
        <div class="evaluation-panel">
            <h2 class="evaluation-panel__title">
                <span>⭐</span>
                Évaluation déjà envoyée
            </h2>

            <div class="evaluation-summary">
                <div class="evaluation-summary__row">
                    <span class="evaluation-summary__label">Note</span>
                    <span class="evaluation-summary__value">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $commande->evaluation->note ? '★' : '☆' }}
                        @endfor
                        ({{ $commande->evaluation->note }}/5)
                    </span>
                </div>

                <div class="evaluation-summary__row">
                    <span class="evaluation-summary__label">Commentaire</span>
                    <span class="evaluation-summary__value">
                        {{ $commande->evaluation->commentaire ?: 'Aucun commentaire' }}
                    </span>
                </div>

                <div class="evaluation-summary__row">
                    <span class="evaluation-summary__label">Date</span>
                    <span class="evaluation-summary__value">{{ $commande->evaluation->created_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>
    @elseif($canEvaluate)
        <div class="evaluation-panel">
            <h2 class="evaluation-panel__title">
                <span>⭐</span>
                Évaluer le service
            </h2>

            <p class="evaluation-panel__subtitle">
                Votre note aide à améliorer l’expérience client et à valoriser le travail du laveur.
            </p>

            <div class="evaluation-form">
                <form action="/client/evaluations/{{ $commande->id }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="evaluation-label">Votre note :</label>
                        <div class="stars-input">
                            <span class="star" data-value="1" onclick="setRating(1)">☆</span>
                            <span class="star" data-value="2" onclick="setRating(2)">☆</span>
                            <span class="star" data-value="3" onclick="setRating(3)">☆</span>
                            <span class="star" data-value="4" onclick="setRating(4)">☆</span>
                            <span class="star" data-value="5" onclick="setRating(5)">☆</span>
                        </div>
                        <input type="hidden" id="note" name="note" value="{{ $initialRating }}" required>
                        @error('note')
                            <p class="text-red-400 text-sm mt-2 text-center">{{ $message }}</p>
                        @enderror
                        <p id="rating-text" class="rating-text">Excellent</p>
                    </div>

                    <div class="form-group">
                        <label for="commentaire" class="evaluation-label">Votre commentaire (optionnel) :</label>
                        <textarea
                            id="commentaire"
                            class="evaluation-textarea"
                            name="commentaire"
                            placeholder="Partagez votre expérience avec ce laveur..."
                        >{{ old('commentaire') }}</textarea>
                        @error('commentaire')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="evaluation-submit">
                        ⭐ Envoyer mon évaluation
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="evaluation-alert">
            <h2 class="text-cyan-custom text-2xl font-bold mb-3">Évaluation non disponible</h2>
            <p class="text-gray-300">
                Cette commande doit être terminée et associée à une mission avant de pouvoir être évaluée.
            </p>
        </div>
    @endif

    <div class="evaluation-actions">
        <a href="/client/commandes/{{ $commande->id }}" class="evaluation-link evaluation-link--secondary">
            ← Retour aux détails
        </a>
        <a href="/client/dashboard" class="evaluation-link evaluation-link--primary">
            Retour au dashboard
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ratingTexts = {
        1: 'Très mauvais',
        2: 'Mauvais',
        3: 'Moyen',
        4: 'Bon',
        5: 'Excellent'
    };

    let currentRating = parseInt(document.getElementById('note')?.value || '5', 10);

    function setRating(rating) {
        currentRating = rating;
        const noteField = document.getElementById('note');
        const ratingText = document.getElementById('rating-text');

        if (noteField) {
            noteField.value = rating;
        }

        if (ratingText) {
            ratingText.textContent = ratingTexts[rating] || ratingTexts[5];
        }

        const stars = document.querySelectorAll('.star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#00C2FF';
                star.textContent = '★';
                star.style.transform = 'scale(1.08)';
            } else {
                star.style.color = '#4a4a4a';
                star.textContent = '☆';
                star.style.transform = 'scale(1)';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setRating(currentRating);

        const stars = document.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-2px) scale(1.12)';
            });

            star.addEventListener('mouseleave', function () {
                const value = parseInt(this.getAttribute('data-value'), 10);
                this.style.transform = value <= currentRating ? 'scale(1.08)' : 'scale(1)';
            });
        });
    });
</script>
@endsection
