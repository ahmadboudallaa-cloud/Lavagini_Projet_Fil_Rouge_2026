@extends('layouts.client')

@section('title', 'Détails de la commande')
@section('page-title', 'Détails de la commande')

@section('styles')
<style>
    .detail-page {
        display: grid;
        gap: 1.5rem;
    }

    .detail-hero {
        background: linear-gradient(135deg, rgba(0, 194, 255, 0.16), rgba(0, 194, 255, 0.04));
        border: 1px solid rgba(0, 194, 255, 0.3);
        border-radius: 30px;
        padding: 2rem;
    }

    .detail-meta-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .detail-meta-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 1rem 1.2rem;
    }

    .detail-meta-label {
        color: #9ca3af;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 0.35rem;
    }

    .detail-meta-value {
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
    }

    .detail-panel {
        background: #1f1f1f;
        border: 1px solid #333333;
        border-radius: 30px;
        padding: 2rem;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
    }

    .detail-panel__title {
        color: #00c2ff;
        font-size: 1.65rem;
        font-weight: 800;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .detail-panel__subtitle {
        color: #cfcfcf;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .detail-summary {
        background: #2b2b2b;
        border: 1px solid #3b3b3b;
        border-radius: 24px;
        padding: 1.5rem;
    }

    .detail-summary__row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.85rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .detail-summary__row:last-child {
        border-bottom: none;
    }

    .detail-summary__label {
        color: #9ca3af;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        flex-shrink: 0;
    }

    .detail-summary__value {
        color: #ffffff;
        font-weight: 700;
        text-align: right;
    }

    .detail-summary__value--muted {
        color: #d1d5db;
        font-weight: 500;
    }

    .detail-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .detail-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.9rem 1.35rem;
        border-radius: 999px;
        font-weight: 800;
        transition: transform 0.2s ease, background 0.2s ease, color 0.2s ease;
    }

    .detail-link:hover {
        transform: translateY(-1px);
    }

    .detail-link--primary {
        background: #00c2ff;
        color: #000000;
    }

    .detail-link--primary:hover {
        background: #00addf;
    }

    .detail-link--secondary {
        background: #ffffff;
        color: #000000;
    }

    .detail-link--secondary:hover {
        background: #e5e7eb;
    }

    .detail-link--success {
        background: #22c55e;
        color: #ffffff;
    }

    .detail-link--success:hover {
        background: #16a34a;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.45rem 0.9rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 800;
        background: rgba(0, 194, 255, 0.12);
        color: #00c2ff;
        border: 1px solid rgba(0, 194, 255, 0.25);
    }

    .status-pill--success {
        background: rgba(34, 197, 94, 0.12);
        color: #4ade80;
        border-color: rgba(34, 197, 94, 0.25);
    }

    .status-pill--warning {
        background: rgba(245, 158, 11, 0.12);
        color: #fbbf24;
        border-color: rgba(245, 158, 11, 0.25);
    }

    .status-pill--gray {
        background: rgba(156, 163, 175, 0.12);
        color: #d1d5db;
        border-color: rgba(156, 163, 175, 0.25);
    }

    .stars-display {
        font-size: 1.2rem;
        letter-spacing: 0.15rem;
        color: #00c2ff;
        white-space: nowrap;
    }

    @media (max-width: 1024px) {
        .detail-meta-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .detail-hero,
        .detail-panel {
            padding: 1.25rem;
            border-radius: 22px;
        }

        .detail-meta-grid {
            grid-template-columns: 1fr;
        }

        .detail-panel__title {
            font-size: 1.3rem;
        }

        .detail-summary {
            padding: 1.2rem;
        }

        .detail-summary__row {
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-summary__value {
            text-align: left;
        }

        .detail-actions {
            flex-direction: column;
        }

        .detail-link {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
@php
    $mission = $commande->mission;
    $canEvaluate = (($commande->statut === 'terminee' || $commande->statut === 'payee') && !$commande->evaluation && $mission);
    $status = strtolower((string) $commande->statut);
    $statusPillClass = match ($status) {
        'terminee', 'payee' => 'status-pill--success',
        'demande' => 'status-pill--warning',
        default => 'status-pill--gray',
    };
@endphp

<div class="detail-page">
    <div class="detail-hero bg-dark-card rounded-[30px] shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-[0.2em] mb-2">Détails de commande</p>
                <h1 class="text-4xl font-extrabold text-white">
                    Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-gray-300 mt-3 max-w-2xl">
                    Consultez les informations complètes de votre commande, de la mission et de l’évaluation.
                </p>
            </div>

            <div class="status-pill {{ $statusPillClass }} self-start">
                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
            </div>
        </div>

        <div class="detail-meta-grid">
            <div class="detail-meta-card">
                <div class="detail-meta-label">Client</div>
                <div class="detail-meta-value">{{ $commande->client->name }}</div>
            </div>

            <div class="detail-meta-card">
                <div class="detail-meta-label">Zone</div>
                <div class="detail-meta-value">{{ $commande->zone ? $commande->zone->nom : 'Non définie' }}</div>
            </div>

            <div class="detail-meta-card">
                <div class="detail-meta-label">Véhicules</div>
                <div class="detail-meta-value">{{ $commande->nombre_vehicules }}</div>
            </div>

            <div class="detail-meta-card">
                <div class="detail-meta-label">Montant</div>
                <div class="detail-meta-value">{{ $commande->montant ? $commande->montant . ' DH' : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="detail-panel">
        <h2 class="detail-panel__title">
            <span>📋</span>
            Informations de la commande
        </h2>

        <div class="detail-summary">
            <div class="detail-summary__row">
                <span class="detail-summary__label">Statut</span>
                <span class="detail-summary__value">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span>
            </div>

            <div class="detail-summary__row">
                <span class="detail-summary__label">Nombre de véhicules</span>
                <span class="detail-summary__value">{{ $commande->nombre_vehicules }}</span>
            </div>

            <div class="detail-summary__row">
                <span class="detail-summary__label">Adresse du service</span>
                <span class="detail-summary__value detail-summary__value--muted">{{ $commande->adresse_service }}</span>
            </div>

            <div class="detail-summary__row">
                <span class="detail-summary__label">Zone géographique</span>
                <span class="detail-summary__value detail-summary__value--muted">
                    {{ $commande->zone ? $commande->zone->nom . ' - ' . $commande->zone->ville : 'Non définie' }}
                </span>
            </div>

            <div class="detail-summary__row">
                <span class="detail-summary__label">Mode de paiement</span>
                <span class="detail-summary__value detail-summary__value--muted">
                    {{ ucfirst(str_replace('_', ' ', $commande->mode_paiement)) }}
                </span>
            </div>

            @if($commande->montant)
                <div class="detail-summary__row">
                    <span class="detail-summary__label">Montant</span>
                    <span class="detail-summary__value">{{ $commande->montant }} DH</span>
                </div>
            @endif

            @if($commande->description)
                <div class="detail-summary__row">
                    <span class="detail-summary__label">Description</span>
                    <span class="detail-summary__value detail-summary__value--muted">
                        {{ $commande->description }}
                    </span>
                </div>
            @endif

            <div class="detail-summary__row">
                <span class="detail-summary__label">Date de création</span>
                <span class="detail-summary__value detail-summary__value--muted">
                    {{ $commande->created_at->format('d/m/Y à H:i') }}
                </span>
            </div>
        </div>
    </div>

    @if($mission)
        <div class="detail-panel">
            <h2 class="detail-panel__title">
                <span>🚗</span>
                Informations mission
            </h2>

            <div class="detail-summary">
                <div class="detail-summary__row">
                    <span class="detail-summary__label">Laveur assigné</span>
                    <span class="detail-summary__value">{{ $mission->laveur ? $mission->laveur->name : 'N/A' }}</span>
                </div>

                <div class="detail-summary__row">
                    <span class="detail-summary__label">Téléphone</span>
                    <span class="detail-summary__value detail-summary__value--muted">{{ $mission->laveur->telephone ?? 'N/A' }}</span>
                </div>

                <div class="detail-summary__row">
                    <span class="detail-summary__label">Statut mission</span>
                    <span class="detail-summary__value">{{ ucfirst(str_replace('_', ' ', $mission->statut ?? 'N/A')) }}</span>
                </div>

                @if($mission->date_debut)
                    <div class="detail-summary__row">
                        <span class="detail-summary__label">Date début</span>
                        <span class="detail-summary__value detail-summary__value--muted">{{ $mission->date_debut->format('d/m/Y à H:i') }}</span>
                    </div>
                @endif

                @if($mission->date_fin)
                    <div class="detail-summary__row">
                        <span class="detail-summary__label">Date fin</span>
                        <span class="detail-summary__value detail-summary__value--muted">{{ $mission->date_fin->format('d/m/Y à H:i') }}</span>
                    </div>
                @endif

                @if($mission->temps_passe)
                    <div class="detail-summary__row">
                        <span class="detail-summary__label">Temps passé</span>
                        <span class="detail-summary__value">{{ $mission->temps_passe }} minutes</span>
                    </div>
                @endif

                @if($mission->commentaire)
                    <div class="detail-summary__row">
                        <span class="detail-summary__label">Commentaire</span>
                        <span class="detail-summary__value detail-summary__value--muted">{{ $mission->commentaire }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($commande->evaluation)
        <div class="detail-panel">
            <h2 class="detail-panel__title">
                <span>⭐</span>
                Votre évaluation
            </h2>

            <div class="detail-summary">
                <div class="detail-summary__row">
                    <span class="detail-summary__label">Note</span>
                    <span class="detail-summary__value">
                        <span class="stars-display">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $commande->evaluation->note ? '★' : '☆' }}
                            @endfor
                        </span>
                        {{ ' (' . $commande->evaluation->note . '/5)' }}
                    </span>
                </div>

                <div class="detail-summary__row">
                    <span class="detail-summary__label">Commentaire</span>
                    <span class="detail-summary__value detail-summary__value--muted">
                        {{ $commande->evaluation->commentaire ?: 'Aucun commentaire' }}
                    </span>
                </div>

                <div class="detail-summary__row">
                    <span class="detail-summary__label">Date</span>
                    <span class="detail-summary__value detail-summary__value--muted">
                        {{ $commande->evaluation->created_at->format('d/m/Y à H:i') }}
                    </span>
                </div>
            </div>
        </div>
    @elseif($canEvaluate)
        <div class="detail-panel">
            <h2 class="detail-panel__title">
                <span>⭐</span>
                Évaluation disponible
            </h2>

            <p class="detail-panel__subtitle">
                Cette commande est terminée. Vous pouvez maintenant laisser votre avis sur la prestation avec la même interface que le dashboard.
            </p>

            <a href="/client/commandes/{{ $commande->id }}/evaluation" class="detail-link detail-link--primary">
                ⭐ Aller à l’évaluation
            </a>
        </div>
    @endif

    <div class="detail-actions">
        @if($commande->statut === 'terminee' && $commande->mode_paiement === 'en_ligne')
            <form action="/paiement/stripe/{{ $commande->id }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="detail-link detail-link--success border-0">
                    💳 Payer maintenant avec Stripe
                </button>
            </form>
        @endif

        <a href="/client/commandes" class="detail-link detail-link--secondary">
            ← Retour à mes commandes
        </a>

        <a href="/client/dashboard" class="detail-link detail-link--primary">
            Retour au dashboard
        </a>
    </div>
</div>
@endsection
