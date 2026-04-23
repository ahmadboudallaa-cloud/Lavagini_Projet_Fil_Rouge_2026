@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('styles')
<style>
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; overflow-y: auto; padding: 2rem 0; }
    .modal-content { background: #1a1a1a; color: white; max-width: 600px; margin: 0 auto; padding: 2rem; border-radius: 15px; border: 1px solid #333; position: relative; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .close { font-size: 2rem; cursor: pointer; color: #999; }
    .close:hover { color: #fff; }
    .form-group { margin-bottom: 1.5rem; }
    .form-group label { display: block; margin-bottom: 0.5rem; color: #ccc; font-weight: bold; }
    .form-group input, .form-group select { width: 100%; padding: 0.8rem; border: 1px solid #444; background: #333; color: white; border-radius: 8px; }
    .btn-full { width: 100%; padding: 1rem; background: #00C2FF; color: black; font-weight: bold; border-radius: 8px; cursor: pointer; border: none; }
</style>
@endsection

@section('content')

<div id="dashboard-header">
    <h2 class="text-4xl font-bold mb-10">
        Bienvenue, <span class="text-cyan-custom font-extrabold">{{ Auth::user()->name }}</span>
    </h2>

    <div class="grid grid-cols-5 gap-5 mb-10">
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $totalCommandes ?? 0 }}</span>
            <span class="text-gray-300 text-sm text-center">Commandes totales</span>
        </div>
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center border-2 border-cyan-custom">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $commandesEnAttente ?? 0 }}</span>
            <span class="text-gray-300 text-sm text-center">En attente</span>
        </div>
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $totalClients ?? 0 }}</span>
            <span class="text-gray-300 text-sm text-center">Clients</span>
        </div>
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $totalLaveurs ?? 0 }}</span>
            <span class="text-gray-300 text-sm text-center">Laveurs</span>
        </div>
        <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center">
            <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $totalZones ?? 0 }}</span>
            <span class="text-gray-300 text-sm text-center">Zones</span>
        </div>
    </div>
</div>

<div id="commandes" class="tab-content active">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <h3 class="text-cyan-custom text-xl font-bold mb-8">Gestion des commandes</h3>
        
        <table class="w-full text-left">
            <thead>
                <tr class="text-white text-lg">
                    <th class="pb-4 font-semibold">ID</th>
                    <th class="pb-4 font-semibold">Client</th>
                    <th class="pb-4 font-semibold">Véhicules</th>
                    <th class="pb-4 font-semibold">Adresse</th>
                    <th class="pb-4 font-semibold text-center">Statut</th>
                    <th class="pb-4 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-200">
                @forelse($commandes as $commande)
                <tr class="hover:bg-dark-hover transition duration-150">
                    <td class="py-4">{{ str_pad($commande->id, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-4">{{ $commande->client->name }}</td>
                    <td class="py-4">{{ $commande->nombre_vehicules }}</td>
                    <td class="py-4 max-w-[200px] truncate text-gray-400">{{ $commande->adresse_service }}</td>
                    <td class="py-4 text-center">
                        @if($commande->statut === 'payee' || $commande->statut === 'payée')
                            <span class="bg-cyan-custom text-black px-4 py-1.5 rounded-full text-sm font-bold">Payée</span>
                        @elseif($commande->statut === 'demande')
                            <span class="bg-yellow-500 text-black px-4 py-1.5 rounded-full text-sm font-bold">Demande</span>
                        @else
                            <span class="bg-gray-500 text-white px-4 py-1.5 rounded-full text-sm font-bold">{{ ucfirst($commande->statut) }}</span>
                        @endif
                    </td>
                    <td class="py-4 flex justify-center space-x-2 items-center">
                        @if($commande->statut === 'demande')
                            <button onclick="openAssignerModal({{ $commande->id }})" class="bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-medium hover:bg-blue-700">Assigner</button>
                        @endif
                        <a href="/admin/commandes/{{ $commande->id }}" class="bg-white text-black px-4 py-1 rounded-full text-sm font-medium hover:bg-gray-200">Voir</a>
                        <form action="/admin/commandes/{{ $commande->id }}" method="POST" class="inline m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded-full text-sm font-medium hover:bg-red-700" onclick="return confirm('Supprimer cette commande ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-6 text-gray-500">Aucune commande</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="missions" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <h3 class="text-cyan-custom text-xl font-bold mb-8">Gestion des missions</h3>
        <table class="w-full text-left">
            <thead>
                <tr class="text-white text-lg border-b border-gray-700">
                    <th class="pb-4 font-semibold">ID</th>
                    <th class="pb-4 font-semibold">Commande</th>
                    <th class="pb-4 font-semibold">Laveur</th>
                    <th class="pb-4 font-semibold">Statut</th>
                    <th class="pb-4 font-semibold">Date</th>
                </tr>
            </thead>
            <tbody class="text-gray-200">
                @forelse($missions ?? [] as $mission)
                <tr class="hover:bg-dark-hover">
                    <td class="py-4">#M{{ str_pad($mission->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-4">#{{ str_pad($mission->commande_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td class="py-4">{{ $mission->laveur->name }}</td>
                    <td class="py-4"><span class="bg-cyan-custom text-black px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst(str_replace('_', ' ', $mission->statut)) }}</span></td>
                    <td class="py-4">{{ $mission->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-6 text-gray-500">Aucune mission</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="clients" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-cyan-custom text-xl font-bold">Gestion des clients</h3>
            <button onclick="openClientModal()" class="bg-cyan-custom text-black px-6 py-2 rounded-full font-bold hover:bg-cyan-400">+ Ajouter</button>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="text-white text-lg border-b border-gray-700">
                    <th class="pb-4 font-semibold">Nom</th>
                    <th class="pb-4 font-semibold">Email</th>
                    <th class="pb-4 font-semibold">Téléphone</th>
                    <th class="pb-4 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-200">
                @forelse($clients ?? [] as $client)
                <tr class="hover:bg-dark-hover">
                    <td class="py-4">{{ $client->name }}</td>
                    <td class="py-4">{{ $client->email }}</td>
                    <td class="py-4">{{ $client->telephone ?? 'N/A' }}</td>
                    <td class="py-4 flex justify-center space-x-2">
                        <button onclick="openModifierClientModal({{ $client->id }}, '{{ $client->name }}', '{{ $client->email }}', '{{ $client->telephone }}')" class="bg-white text-black px-4 py-1 rounded-full text-sm font-medium">Modifier</button>
                        <form action="/admin/clients/{{ $client->id }}" method="POST" class="inline m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded-full text-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-6 text-gray-500">Aucun client</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="laveurs" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-cyan-custom text-xl font-bold">Gestion des laveurs</h3>
            <button onclick="openLaveurModal()" class="bg-cyan-custom text-black px-6 py-2 rounded-full font-bold hover:bg-cyan-400">+ Ajouter</button>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="text-white text-lg border-b border-gray-700">
                    <th class="pb-4 font-semibold">Nom</th>
                    <th class="pb-4 font-semibold">Email</th>
                    <th class="pb-4 font-semibold">Téléphone</th>
                    <th class="pb-4 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-200">
                @forelse($laveurs ?? [] as $laveur)
                <tr class="hover:bg-dark-hover">
                    <td class="py-4">{{ $laveur->name }}</td>
                    <td class="py-4">{{ $laveur->email }}</td>
                    <td class="py-4">{{ $laveur->telephone ?? 'N/A' }}</td>
                    <td class="py-4 flex justify-center space-x-2">
                        <button onclick="openModifierLaveurModal({{ $laveur->id }}, '{{ $laveur->name }}', '{{ $laveur->email }}')" class="bg-white text-black px-4 py-1 rounded-full text-sm">Modifier</button>
                        <form action="/admin/laveurs/{{ $laveur->id }}" method="POST" class="inline m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded-full text-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-6 text-gray-500">Aucun laveur</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="zones" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-cyan-custom text-xl font-bold">Gestion des zones</h3>
            <button onclick="openZoneModal()" class="bg-cyan-custom text-black px-6 py-2 rounded-full font-bold hover:bg-cyan-400">+ Ajouter</button>
        </div>
        <table class="w-full text-left">
            <thead>
                <tr class="text-white text-lg border-b border-gray-700">
                    <th class="pb-4 font-semibold">Nom</th>
                    <th class="pb-4 font-semibold">Ville</th>
                    <th class="pb-4 font-semibold">Code Postal</th>
                    <th class="pb-4 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-200">
                @forelse($zones ?? [] as $zone)
                <tr class="hover:bg-dark-hover">
                    <td class="py-4">{{ $zone->nom }}</td>
                    <td class="py-4">{{ $zone->ville }}</td>
                    <td class="py-4">{{ $zone->code_postal }}</td>
                    <td class="py-4 flex justify-center space-x-2">
                        <button onclick="openModifierZoneModal({{ $zone->id }}, '{{ $zone->nom }}', '{{ $zone->ville }}', '{{ $zone->code_postal }}')" class="bg-white text-black px-4 py-1 rounded-full text-sm">Modifier</button>
                        <form action="/admin/zones/{{ $zone->id }}" method="POST" class="inline m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded-full text-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-6 text-gray-500">Aucune zone</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="parametres" class="tab-content">
    <div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
        <h3 class="text-cyan-custom text-xl font-bold mb-8">Paramètres du compte</h3>
        
        <form action="/admin/profil/update" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                <button type="button" onclick="showTab('commandes', document.querySelector('.menu-item'))" class="px-6 py-3 bg-gray-600 text-white rounded-full font-bold hover:bg-gray-700">Annuler</button>
                <button type="submit" class="px-6 py-3 bg-cyan-custom text-black rounded-full font-bold hover:bg-cyan-400">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</div>

<div id="laveurModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-cyan-custom font-bold text-xl">Ajouter un laveur</h2>
            <span class="close" onclick="closeLaveurModal()">&times;</span>
        </div>
        <form action="/admin/laveurs" method="POST">
            @csrf
            <div class="form-group"><label>Nom complet</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
            <button type="submit" class="btn-full">Créer le laveur</button>
        </form>
    </div>
</div>

<div id="clientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-cyan-custom font-bold text-xl">Ajouter un client</h2>
            <span class="close" onclick="closeClientModal()">&times;</span>
        </div>
        <form action="/admin/clients" method="POST">
            @csrf
            <div class="form-group"><label>Nom complet</label><input type="text" name="name" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Téléphone</label><input type="text" name="telephone"></div>
            <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
            <button type="submit" class="btn-full">Créer le client</button>
        </form>
    </div>
</div>

<div id="zoneModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-cyan-custom font-bold text-xl">Ajouter une zone</h2>
            <span class="close" onclick="closeZoneModal()">&times;</span>
        </div>
        <form action="/admin/zones" method="POST">
            @csrf
            <div class="form-group"><label>Nom de la zone</label><input type="text" name="nom" required></div>
            <div class="form-group"><label>Ville</label><input type="text" name="ville" required></div>
            <div class="form-group"><label>Code postal</label><input type="text" name="code_postal" required></div>
            <button type="submit" class="btn-full">Créer la zone</button>
        </form>
    </div>
</div>

<div id="modifierZoneModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-cyan-custom font-bold text-xl">Modifier la zone</h2>
            <span class="close" onclick="closeModifierZoneModal()">&times;</span>
        </div>
        <form id="modifierZoneForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label>Nom de la zone</label><input type="text" name="nom" id="zone_nom" required></div>
            <div class="form-group"><label>Ville</label><input type="text" name="ville" id="zone_ville" required></div>
            <div class="form-group"><label>Code postal</label><input type="text" name="code_postal" id="zone_code_postal" required></div>
            <button type="submit" class="btn-full">Modifier la zone</button>
        </form>
    </div>
</div>

<div id="modifierClientModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-cyan-custom font-bold text-xl">Modifier le client</h2>
            <span class="close" onclick="closeModifierClientModal()">&times;</span>
        </div>
        <form id="modifierClientForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label>Nom complet</label><input type="text" name="name" id="client_name" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" id="client_email" required></div>
            <div class="form-group"><label>Téléphone</label><input type="text" name="telephone" id="client_telephone"></div>
            <button type="submit" class="btn-full">Modifier le client</button>
        </form>
    </div>
</div>

<div id="modifierLaveurModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="text-cyan-custom font-bold text-xl">Modifier le laveur</h2>
            <span class="close" onclick="closeModifierLaveurModal()">&times;</span>
        </div>
        <form id="modifierLaveurForm" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label>Nom complet</label><input type="text" name="name" id="laveur_name" required></div>
            <div class="form-group"><label>Email</label><input type="email" name="email" id="laveur_email" required></div>
            <button type="submit" class="btn-full">Modifier le laveur</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openLaveurModal() { document.getElementById('laveurModal').style.display = 'block'; }
    function closeLaveurModal() { document.getElementById('laveurModal').style.display = 'none'; }
    function openZoneModal() { document.getElementById('zoneModal').style.display = 'block'; }
    function closeZoneModal() { document.getElementById('zoneModal').style.display = 'none'; }
    function openClientModal() { document.getElementById('clientModal').style.display = 'block'; }
    function closeClientModal() { document.getElementById('clientModal').style.display = 'none'; }
    
    function openModifierZoneModal(id, nom, ville, code_postal) {
        document.getElementById('zone_nom').value = nom;
        document.getElementById('zone_ville').value = ville;
        document.getElementById('zone_code_postal').value = code_postal;
        document.getElementById('modifierZoneForm').action = '/admin/zones/' + id;
        document.getElementById('modifierZoneModal').style.display = 'block';
    }
    function closeModifierZoneModal() { document.getElementById('modifierZoneModal').style.display = 'none'; }
    
    function openModifierClientModal(id, name, email, telephone) {
        document.getElementById('client_name').value = name;
        document.getElementById('client_email').value = email;
        document.getElementById('client_telephone').value = telephone || '';
        document.getElementById('modifierClientForm').action = '/admin/clients/' + id;
        document.getElementById('modifierClientModal').style.display = 'block';
    }
    function closeModifierClientModal() { document.getElementById('modifierClientModal').style.display = 'none'; }
    
    function openModifierLaveurModal(id, name, email) {
        document.getElementById('laveur_name').value = name;
        document.getElementById('laveur_email').value = email;
        document.getElementById('modifierLaveurForm').action = '/admin/laveurs/' + id;
        document.getElementById('modifierLaveurModal').style.display = 'block';
    }
    function closeModifierLaveurModal() { document.getElementById('modifierLaveurModal').style.display = 'none'; }
    
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
    
    // Fonction pour gérer l'affichage du header selon l'onglet
    function toggleDashboardHeader(tabName) {
        const header = document.getElementById('dashboard-header');
        if (tabName === 'parametres') {
            header.style.display = 'none';
        } else {
            header.style.display = 'block';
        }
    }
    
    // Modifier la fonction showTab existante dans admin.blade.php
    const originalShowTab = window.showTab;
    window.showTab = function(tabName, element) {
        toggleDashboardHeader(tabName);
        if (originalShowTab) originalShowTab(tabName, element);
    };
    
    // Au chargement de la page
    window.addEventListener('DOMContentLoaded', () => {
        const activeTab = localStorage.getItem('activeTab') || 'commandes';
        toggleDashboardHeader(activeTab);
    });
    
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection