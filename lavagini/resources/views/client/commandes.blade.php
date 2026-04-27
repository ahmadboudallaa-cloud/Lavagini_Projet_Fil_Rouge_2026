@extends('layouts.client')

@section('title', 'Mes commandes')
@section('page-title', 'Mes Commandes')

@section('content')

<div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
    <h3 class="text-cyan-custom text-2xl font-bold mb-8">Historique de mes commandes</h3>
    
    <div class="space-y-4">
        @forelse($commandes as $commande)
        <div class="border border-gray-700 rounded-xl p-6 hover:bg-dark-hover transition">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-3">
                    <strong class="text-white text-xl">Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}</strong>
                    
                    @if($commande->statut === 'payee' || $commande->statut === 'payée')
                        <span class="bg-cyan-custom text-black px-3 py-1 rounded-full text-xs font-bold">Payée</span>
                    @elseif($commande->statut === 'demande')
                        <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-xs font-bold">Demande</span>
                    @elseif($commande->statut === 'en_cours')
                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">En cours</span>
                    @elseif($commande->statut === 'terminee')
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">Terminée</span>
                    @else
                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span>
                    @endif
                </div>
                <span class="text-gray-400 text-sm">{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-gray-400 text-sm mb-1">Adresse</p>
                    <p class="text-white">{{ $commande->adresse_service }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm mb-1">Véhicules</p>
                    <p class="text-white">{{ $commande->nombre_vehicules }} véhicule(s)</p>
                </div>
                @if($commande->zone)
                <div>
                    <p class="text-gray-400 text-sm mb-1">Zone</p>
                    <p class="text-white">{{ $commande->zone->nom }} - {{ $commande->zone->ville }}</p>
                </div>
                @endif
                @if($commande->mission)
                <div>
                    <p class="text-gray-400 text-sm mb-1">Laveur</p>
                    <p class="text-white">{{ $commande->mission->laveur->name }}</p>
                </div>
                @endif
                <div>
                    <p class="text-gray-400 text-sm mb-1">Montant</p>
                    <p class="text-cyan-custom font-bold text-lg">{{ number_format($commande->montant, 2) }} DH</p>
                </div>
            </div>

            <div class="flex space-x-3 pt-4 border-t border-gray-700">
                <a href="/client/commandes/{{ $commande->id }}" class="bg-white text-black px-5 py-2 rounded-full font-bold text-sm hover:bg-gray-200">Voir détails</a>
                
                @if(($commande->statut === 'terminee' || $commande->statut === 'payee') && !$commande->evaluation && $commande->mission)
                <a href="/client/commandes/{{ $commande->id }}" class="bg-cyan-custom text-black px-5 py-2 rounded-full font-bold text-sm hover:bg-cyan-400">Évaluer</a>
                @endif

                @if($commande->evaluation)
                <span class="bg-green-600 text-white px-5 py-2 rounded-full text-sm font-bold">Évaluée</span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="text-gray-500 mb-4">
                <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-white text-xl font-bold mb-2">Aucune commande</h3>
            <p class="text-gray-400 mb-6">Vous n'avez pas encore passé de commande</p>
            <a href="/client/dashboard" class="bg-cyan-custom text-black px-6 py-3 rounded-full font-bold hover:bg-cyan-400">Créer une commande</a>
        </div>
        @endforelse
    </div>
</div>

@endsection
