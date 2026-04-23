@extends('layouts.client')

@section('title', 'Dashboard Client')
@section('page-title', 'Dashboard Client')

@section('styles')
<style>
    /* Style de la modale en mode Sombre pour coller au design */
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; overflow-y: auto; padding: 2rem 0; }
    .modal-content { background: #1a1a1a; max-width: 600px; margin: 0 auto; padding: 2.5rem; border-radius: 20px; border: 1px solid #333; position: relative; color: white; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .close { font-size: 2rem; cursor: pointer; color: #999; transition: color 0.2s; }
    .close:hover { color: #00C2FF; }
    
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; color: #ccc; font-weight: 600; }
    .form-group input, .form-group select, .form-group textarea { 
        width: 100%; padding: 0.8rem 1rem; background: #333333; border: 1px solid #444; border-radius: 10px; color: white; outline: none; transition: border 0.3s;
    }
    .form-group input:focus, .form-group select:focus { border-color: #00C2FF; }
    .btn-primary { background: #00C2FF; color: black; font-weight: bold; border-radius: 10px; padding: 1rem; width: 100%; cursor: pointer; transition: background 0.3s; border: none; }
    .btn-primary:hover { background: #0099cc; }
</style>
@endsection

@section('content')

<div id="dashboard-main" style="display: block;">
    <h2 class="text-4xl font-bold mb-10">
        Bienvenue, <span class="text-cyan-custom font-extrabold">{{ Auth::user()->name }}</span>
    </h2>

    <div class="grid grid-cols-3 gap-6 mb-10">
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center border-2 border-transparent">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $totalCommandes ?? 0 }}</span>
            <span class="text-gray-300 text-sm font-medium">Commandes totales</span>
        </div>
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center border-2 border-cyan-custom">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $commandesEnCours ?? 0 }}</span>
            <span class="text-gray-300 text-sm font-medium">En cours</span>
        </div>
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center border-2 border-transparent">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $commandesTerminees ?? 0 }}</span>
            <span class="text-gray-300 text-sm font-medium">Terminées</span>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-12">
        <div onclick="openModal()" class="bg-dark-card rounded-2xl p-6 text-center cursor-pointer border-2 border-transparent hover:border-cyan-custom transition duration-300 group">
            <div class="text-cyan-custom mb-3 flex justify-center">
                <svg class="w-10 h-10 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Nouvelle commande</h3>
            <p class="text-gray-400 text-sm">Réserver un lavage</p>
        </div>

        <div class="bg-dark-card rounded-2xl p-6 text-center cursor-pointer border-2 border-transparent hover:border-cyan-custom transition duration-300 group">
            <div class="text-cyan-custom mb-3 flex justify-center">
                <svg class="w-10 h-10 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
            <h3 class="text-white font-bold text-lg mb-1">Évaluations</h3>
            <p class="text-gray-400 text-sm">Noter les laveurs</p>
        </div>
    </div>

    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <h3 class="text-cyan-custom text-2xl font-bold mb-8">Mes dernières commandes</h3>
        
        <div class="space-y-4">
            @forelse($dernieresCommandes ?? [] as $commande)
            <div class="border-b border-gray-700 pb-4 flex justify-between items-center hover:bg-dark-hover transition px-4 rounded-xl">
                <div>
                    <div class="flex items-center space-x-3 mb-1">
                        <strong class="text-white text-lg">Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}</strong>
                        
                        @if($commande->statut === 'payee' || $commande->statut === 'payée')
                            <span class="bg-cyan-custom text-black px-3 py-1 rounded-full text-xs font-bold">Payée</span>
                        @elseif($commande->statut === 'demande')
                            <span class="bg-yellow-500 text-black px-3 py-1 rounded-full text-xs font-bold">Demande</span>
                        @elseif($commande->statut === 'en_cours')
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold">En cours</span>
                        @else
                            <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span>
                        @endif
                    </div>
                    <p class="text-gray-300">{{ $commande->nombre_vehicules }} véhicule(s) - <span class="italic text-gray-400">{{ $commande->adresse_service }}</span></p>
                    <p class="text-sm text-gray-500 mt-1">Date: {{ $commande->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <a href="/client/commandes/{{ $commande->id }}" class="bg-white text-black px-5 py-2 rounded-full font-bold text-sm hover:bg-gray-200">Détails</a>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-gray-400 italic">
                Aucune commande pour le moment. Réservez votre premier lavage !
            </div>
            @endforelse
        </div>
    </div>
</div>

<div id="commandes" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <h3 class="text-cyan-custom text-2xl font-bold mb-8">Historique de mes commandes</h3>
        
        <div class="space-y-6">
            @forelse($dernieresCommandes ?? [] as $commande)
            <div class="bg-dark-hover border-2 border-gray-700 rounded-2xl p-6 hover:border-cyan-custom transition duration-300">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-700">
                    <div class="flex items-center space-x-4">
                        <div class="bg-cyan-custom/20 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-cyan-custom" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-white text-xl block">Commande #{{ str_pad($commande->id, 3, '0', STR_PAD_LEFT) }}</strong>
                            <span class="text-gray-400 text-sm">{{ $commande->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>
                    
                    @if($commande->statut === 'payee' || $commande->statut === 'payée')
                        <span class="bg-cyan-custom text-black px-4 py-2 rounded-full text-sm font-bold">Payée</span>
                    @elseif($commande->statut === 'demande')
                        <span class="bg-yellow-500 text-black px-4 py-2 rounded-full text-sm font-bold">Demande</span>
                    @elseif($commande->statut === 'en_cours')
                        <span class="bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-bold">En cours</span>
                    @elseif($commande->statut === 'terminee')
                        <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold">Terminée</span>
                    @else
                        <span class="bg-gray-500 text-white px-4 py-2 rounded-full text-sm font-bold">{{ ucfirst(str_replace('_', ' ', $commande->statut)) }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div class="bg-black/30 p-4 rounded-xl">
                        <p class="text-gray-400 text-xs mb-2 uppercase tracking-wider">Adresse</p>
                        <p class="text-white font-medium">{{ Str::limit($commande->adresse_service, 40) }}</p>
                    </div>
                    <div class="bg-black/30 p-4 rounded-xl">
                        <p class="text-gray-400 text-xs mb-2 uppercase tracking-wider">Véhicules</p>
                        <p class="text-white font-bold text-lg">{{ $commande->nombre_vehicules }}</p>
                    </div>
                    @if($commande->zone)
                    <div class="bg-black/30 p-4 rounded-xl">
                        <p class="text-gray-400 text-xs mb-2 uppercase tracking-wider">Zone</p>
                        <p class="text-white font-medium">{{ $commande->zone->nom }}</p>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    @if($commande->mission)
                    <div class="flex items-center space-x-3">
                        <div class="bg-cyan-custom/10 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-cyan-custom" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs">Laveur assigné</p>
                            <p class="text-white font-medium">{{ $commande->mission->laveur->name }}</p>
                        </div>
                    </div>
                    @endif
                    @if($commande->montant)
                    <div class="flex items-center space-x-3">
                        <div class="bg-cyan-custom/10 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-cyan-custom" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs">Montant total</p>
                            <p class="text-cyan-custom font-bold text-xl">{{ $commande->montant }} €</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex space-x-3 pt-4 border-t border-gray-700">
                    <a href="/client/commandes/{{ $commande->id }}" class="bg-white text-black px-6 py-2.5 rounded-full font-bold text-sm hover:bg-gray-200 transition flex items-center space-x-2">
                        <span>Voir détails</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    @if(($commande->statut === 'terminee' || $commande->statut === 'payee') && !$commande->evaluation && $commande->mission)
                    <a href="/client/commandes/{{ $commande->id }}" class="bg-cyan-custom text-black px-6 py-2.5 rounded-full font-bold text-sm hover:bg-cyan-400 transition">⭐ Évaluer</a>
                    @endif

                    @if($commande->evaluation)
                    <span class="bg-green-600 text-white px-6 py-2.5 rounded-full text-sm font-bold">Évaluée</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <div class="bg-dark-hover rounded-2xl p-12 inline-block">
                    <div class="text-gray-600 mb-6">
                        <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-white text-2xl font-bold mb-3">Aucune commande</h3>
                    <p class="text-gray-400 mb-8 text-lg">Vous n'avez pas encore passé de commande</p>
                    <button onclick="openModal()" class="bg-cyan-custom text-black px-8 py-3 rounded-full font-bold hover:bg-cyan-400 transition">+ Créer une commande</button>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div id="factures" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <h3 class="text-cyan-custom text-2xl font-bold mb-8">Mes factures</h3>
        
        <div class="space-y-5">
            @php
                $factures = auth()->user()->commandes()->whereHas('facture')->with('facture')->get()->pluck('facture');
            @endphp
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
</div>

<div id="profil" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <h3 class="text-cyan-custom text-xl font-bold mb-8">Mon Profil</h3>
        
        <form action="/client/profil/update" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="flex items-center space-x-6 mb-8">
                <div class="w-24 h-24 bg-gray-600 rounded-full flex items-center justify-center overflow-hidden">
                    @if(auth()->user()->photo_profile)
                        <img src="{{ asset('uploads/profiles/' . auth()->user()->photo_profile) }}" class="w-full h-full object-cover" id="profilePreview">
                    @else
                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    @endif
                </div>
                <div>
                    <label class="bg-cyan-custom text-black px-6 py-2 rounded-full font-bold cursor-pointer hover:bg-cyan-400">
                        Changer la photo
                        <input type="file" name="photo_profile" accept="image/*" class="hidden" onchange="previewImage(event)">
                    </label>
                    <p class="text-gray-400 text-sm mt-2">JPG, PNG ou GIF (Max. 2MB)</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-300 font-semibold mb-2">Nom complet</label>
                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-gray-300 font-semibold mb-2">Email</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-300 font-semibold mb-2">Téléphone</label>
                    <input type="text" name="telephone" value="{{ auth()->user()->telephone }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-300 font-semibold mb-2">Adresse</label>
                    <input type="text" name="adresse" value="{{ auth()->user()->adresse }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none">
                </div>
            </div>

            <div class="border-t border-gray-700 pt-6 mt-6">
                <h4 class="text-white font-bold mb-4">Changer le mot de passe</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Nouveau mot de passe</label>
                        <input type="password" name="password" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none" placeholder="Laisser vide pour ne pas changer">
                    </div>
                    <div>
                        <label class="block text-gray-300 font-semibold mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none" placeholder="Confirmer le nouveau mot de passe">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <button type="button" onclick="window.location.href='/client/dashboard'" class="px-6 py-3 bg-gray-600 text-white rounded-full font-bold hover:bg-gray-700">Annuler</button>
                <button type="submit" class="px-6 py-3 bg-cyan-custom text-black rounded-full font-bold hover:bg-cyan-400">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>

<div id="commandeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-cyan-custom text-2xl font-bold">Nouvelle commande</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        
        <form action="/commandes" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nombre_vehicules">Nombre de véhicules</label>
                <input type="number" id="nombre_vehicules" name="nombre_vehicules" min="1" value="1" required>
            </div>

            <div class="form-group">
                <label for="type_service">Type de service</label>
                <select id="type_service" name="type_service" required onchange="calculerMontant()">
                    <option value="lavage_standard">Lavage Standard - 25€/véhicule</option>
                    <option value="lavage_complet">Lavage Complet - 45€/véhicule</option>
                    <option value="lavage_premium">Lavage Premium - 65€/véhicule</option>
                </select>
            </div>

            <div class="form-group">
                <label for="adresse_service">Adresse complète</label>
                <input type="text" id="adresse_service" name="adresse_service" placeholder="123 rue de Paris, 75000" required>
            </div>

            <div class="form-group">
                <label for="zone_id">Zone géographique</label>
                <select id="zone_id" name="zone_id" required>
                    <option value="">Sélectionner une zone</option>
                    @foreach($zones ?? [] as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->nom }} - {{ $zone->ville }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="mode_paiement">Mode de paiement</label>
                <select id="mode_paiement" name="mode_paiement" required>
                    <option value="en_ligne">Paiement en ligne immédiat</option>
                    <option value="fin_service">Paiement à la fin du service (Espèces/CB)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Instructions pour le laveur (optionnel)</label>
                <textarea id="description" name="description" rows="3" placeholder="Ex: Garez-vous au fond du parking..."></textarea>
            </div>

            <div class="bg-black p-4 rounded-xl mb-6 text-center border border-gray-700">
                <span class="text-gray-400 uppercase text-xs tracking-wider block mb-1">Montant estimé</span>
                <span id="montant_estime" class="text-cyan-custom text-3xl font-extrabold">25€</span>
            </div>

            <button type="submit" class="btn-primary">Confirmer la commande</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal() { document.getElementById('commandeModal').style.display = 'block'; }
    function closeModal() { document.getElementById('commandeModal').style.display = 'none'; }

    function calculerMontant() {
        const nbVehicules = document.getElementById('nombre_vehicules').value || 1;
        const typeService = document.getElementById('type_service').value;
        const tarifs = { 'lavage_standard': 25, 'lavage_complet': 45, 'lavage_premium': 65 };
        const montant = nbVehicules * tarifs[typeService];
        document.getElementById('montant_estime').textContent = montant + '€';
    }
    
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('profilePreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const parent = event.target.closest('.flex').querySelector('.w-24');
                    parent.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover" id="profilePreview">';
                }
            }
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('nombre_vehicules').addEventListener('input', calculerMontant);
    });

    window.onclick = function(event) {
        const modal = document.getElementById('commandeModal');
        if (event.target == modal) modal.style.display = 'none';
    }
</script>
@endsection