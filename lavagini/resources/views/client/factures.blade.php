@extends('layouts.client')

@section('title', 'Mes factures')
@section('page-title', 'Mes Factures')

@section('content')

<div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
    <h3 class="text-cyan-custom text-2xl font-bold mb-8">Mes factures</h3>
    
    <div class="space-y-5">
        @forelse($factures as $facture)
        <div class="bg-dark-hover border-2 border-gray-700 rounded-2xl p-6 hover:border-cyan-custom transition duration-300">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-6 flex-1">
                    <div class="bg-cyan-custom/20 p-4 rounded-xl">
                        <svg class="w-8 h-8 text-cyan-custom" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-white text-xl font-bold mb-2">{{ $facture->numero_facture }}</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Commande</p>
                                <p class="text-white font-medium">#{{ str_pad($facture->commande_id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Date</p>
                                <p class="text-white font-medium">{{ $facture->date_facture->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Service</p>
                                <p class="text-white font-medium">{{ ucfirst(str_replace('_', ' ', $facture->commande->type_service)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <p class="text-gray-400 text-sm mb-1">Montant</p>
                        <p class="text-cyan-custom text-3xl font-bold">{{ $facture->montant }}€</p>
                    </div>
                    <a href="/factures/{{ $facture->id }}/telecharger" class="bg-white text-black px-6 py-3 rounded-full font-bold hover:bg-gray-200 transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Télécharger</span>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <div class="bg-dark-hover rounded-2xl p-12 inline-block">
                <div class="text-gray-600 mb-6">
                    <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-white text-2xl font-bold mb-3">Aucune facture</h3>
                <p class="text-gray-400 mb-8 text-lg">Vous n'avez pas encore de facture</p>
                <button onclick="showTab('dashboard', document.querySelector('.menu-item'))" class="bg-cyan-custom text-black px-8 py-3 rounded-full font-bold hover:bg-cyan-400 transition">Retour au dashboard</button>
            </div>
        </div>
        @endforelse
    </div>
</div>

@endsection
