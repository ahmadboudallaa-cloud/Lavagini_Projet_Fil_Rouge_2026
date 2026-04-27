@extends('layouts.laveur')

@section('title', 'Dashboard Laveur')
@section('page-title', 'Tableau de bord')

@section('styles')
<style>
    /* Responsive Dashboard Laveur - Optimisé */
    @media (max-width: 1024px) {
        .text-4xl { font-size: 2rem !important; }
        .text-2xl { font-size: 1.5rem !important; }
        .grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
        table { display: block; overflow-x: auto; white-space: nowrap; }
    }
    
    @media (max-width: 768px) {
        /* Textes */
        .text-4xl { font-size: 1.75rem !important; }
        .text-3xl { font-size: 1.5rem !important; }
        .text-2xl { font-size: 1.25rem !important; }
        .text-xl { font-size: 1.125rem !important; }
        
        /* Grilles */
        .grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; }
        .grid-cols-2 { grid-template-columns: 1fr !important; }
        
        /* Espacements */
        .mb-12 { margin-bottom: 2rem !important; }
        .gap-6 { gap: 1rem !important; }
        .p-10 { padding: 1.5rem !important; }
        .p-6 { padding: 1.25rem !important; }
        .rounded-\[40px\] { border-radius: 20px !important; }
        
        /* Tables */
        table { font-size: 0.875rem !important; }
        table th, table td { padding: 0.5rem !important; }
        
        /* Boutons actions */
        .flex.justify-center.items-center.gap-2 {
            flex-direction: column !important;
            width: 100% !important;
        }
        
        .flex.justify-center.items-center.gap-2 form,
        .flex.justify-center.items-center.gap-2 a {
            width: 100% !important;
        }
        
        .flex.justify-center.items-center.gap-2 button,
        .flex.justify-center.items-center.gap-2 a {
            width: 100% !important;
            text-align: center !important;
            justify-content: center !important;
        }
    }
    
    @media (max-width: 640px) {
        /* Textes */
        .text-4xl { font-size: 1.5rem !important; }
        .text-3xl { font-size: 1.25rem !important; }
        .text-2xl { font-size: 1.125rem !important; }
        .text-xl { font-size: 1rem !important; }
        .text-lg { font-size: 0.95rem !important; }
        
        /* Espacements */
        .p-10 { padding: 1rem !important; }
        .p-6 { padding: 0.875rem !important; }
        
        /* Grilles */
        .grid-cols-4 { grid-template-columns: 1fr !important; }
        
        /* Tables scrollables */
        table { display: block; overflow-x: auto; white-space: nowrap; }
    }
    
    @media (max-width: 480px) {
        /* Textes */
        .text-4xl { font-size: 1.25rem !important; }
        .text-3xl { font-size: 1.125rem !important; }
        .text-2xl { font-size: 1rem !important; }
        .text-xl { font-size: 0.95rem !important; }
    }
</style>
@endsection

@section('content')
<h2 class="text-4xl font-bold mb-10">
    Bienvenue, <span class="text-cyan-custom font-extrabold">{{ Auth::user()->name }}</span>
</h2>

<div class="grid grid-cols-4 gap-6 mb-12">
    <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center">
        <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $totalMissions ?? 0 }}</span>
        <span class="text-gray-300 text-sm text-center">Missions totales</span>
    </div>
    <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center border-2 border-cyan-custom">
        <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $missionsEnCours ?? 0 }}</span>
        <span class="text-gray-300 text-sm text-center">En cours</span>
    </div>
    <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center">
        <span class="text-cyan-custom text-4xl font-bold mb-2">{{ number_format($noteMoyenne ?? 0, 1) }}</span>
        <span class="text-gray-300 text-sm text-center">Note moyenne</span>
    </div>
    <div class="bg-dark-card rounded-2xl p-6 flex flex-col items-center justify-center">
        <span class="text-cyan-custom text-4xl font-bold mb-2">{{ $missions->where('statut', 'terminee')->count() ?? 0 }}</span>
        <span class="text-gray-300 text-sm text-center">Terminees</span>
    </div>
</div>

<div class="bg-dark-card rounded-[40px] p-10 shadow-2xl mb-12">
    <h3 class="text-cyan-custom text-2xl font-bold mb-8">Mes missions</h3>

    <table class="w-full text-left">
        <thead>
            <tr class="text-xl border-b border-gray-800">
                <th class="pb-4 font-semibold">Mission</th>
                <th class="pb-4 font-semibold">Client</th>
                <th class="pb-4 font-semibold">Vehicules</th>
                <th class="pb-4 font-semibold">Adresse</th>
                <th class="pb-4 font-semibold text-center">Statut</th>
                <th class="pb-4 font-semibold text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-lg">
            @forelse($missions as $mission)
            <tr class="hover:bg-dark-hover transition duration-150 border-b border-gray-800/50 last:border-0">
                <td class="py-6">
                    <span class="block">#M{{ str_pad($mission->id, 3, '0', STR_PAD_LEFT) }}</span>
                    <span class="text-sm text-gray-500">{{ $mission->created_at->format('d/m/Y') }}</span>
                </td>
                <td class="py-6">{{ $mission->commande->client->name }}</td>
                <td class="py-6">{{ $mission->commande->nombre_vehicules }}</td>
                <td class="py-6 italic text-gray-400 truncate max-w-[200px]">{{ $mission->commande->adresse_service }}</td>
                <td class="py-6 text-center align-middle">
                    @if($mission->statut === 'assignee')
                        <span class="inline-flex min-w-[118px] items-center justify-center bg-blue-600 text-white px-5 py-1.5 rounded-full font-bold text-sm">Assignee</span>
                    @elseif($mission->statut === 'en_cours')
                        <span class="inline-flex min-w-[118px] items-center justify-center bg-yellow-500 text-black px-5 py-1.5 rounded-full font-bold text-sm">En cours</span>
                    @elseif($mission->statut === 'terminee')
                        <span class="inline-flex min-w-[118px] items-center justify-center bg-cyan-custom text-black px-5 py-1.5 rounded-full font-bold text-sm">Terminee</span>
                    @else
                        <span class="inline-flex min-w-[118px] items-center justify-center bg-gray-500 text-white px-5 py-1.5 rounded-full font-bold text-sm">{{ ucfirst(str_replace('_', ' ', $mission->statut)) }}</span>
                    @endif
                </td>
                <td class="py-6 text-center align-middle">
                    <div class="flex justify-center items-center gap-2">
                        @if($mission->statut === 'assignee')
                            <form action="/laveur/missions/{{ $mission->id }}/demarrer" method="POST" class="inline m-0">
                                @csrf
                                <button type="submit" class="bg-green-500 text-black px-5 py-1.5 rounded-full font-bold text-sm hover:bg-green-400">Demarrer</button>
                            </form>
                        @elseif($mission->statut === 'en_cours')
                            <form action="/laveur/missions/{{ $mission->id }}/terminer" method="POST" class="inline m-0">
                                @csrf
                                <button type="submit" class="bg-cyan-custom text-black px-5 py-1.5 rounded-full font-bold text-sm hover:bg-[#00a3d9]">Terminer</button>
                            </form>
                        @endif

                        <a href="/laveur/missions/{{ $mission->id }}" class="bg-white text-black px-5 py-1.5 rounded-full font-bold text-sm hover:bg-gray-200 inline-block">Details</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-10 text-gray-500 italic">Aucune mission pour le moment.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bg-dark-card rounded-[40px] p-10 shadow-2xl">
    <h3 class="text-cyan-custom text-2xl font-bold mb-8">Mes evaluations recentes</h3>

    <div class="grid grid-cols-2 gap-6">
        @forelse($dernieresEvaluations as $evaluation)
            <div class="bg-[#2b2b2b] rounded-3xl p-6 border border-gray-700 hover:border-cyan-custom transition duration-300">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <strong class="text-white text-lg block">{{ $evaluation->client->name }}</strong>
                        <span class="text-gray-400 text-sm">{{ $evaluation->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="text-cyan-custom text-2xl tracking-widest">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $evaluation->note ? '★' : '☆' }}
                        @endfor
                    </div>
                </div>

                @if($evaluation->commentaire)
                    <p class="text-gray-300 italic">"{{ $evaluation->commentaire }}"</p>
                @else
                    <p class="text-gray-600 italic">Aucun commentaire laisse par le client.</p>
                @endif
            </div>
        @empty
            <div class="col-span-2 text-center py-8 text-gray-500 italic">
                Aucune evaluation pour le moment.
            </div>
        @endforelse
    </div>
</div>
@endsection
