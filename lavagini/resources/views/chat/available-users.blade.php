@php
    $layout = auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'laveur' ? 'layouts.laveur' : 'layouts.client');
@endphp

@extends($layout)

@section('content')
<div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
    <div class="flex justify-between items-center mb-8 gap-4 flex-wrap">
        <div>
            <h3 class="text-cyan-custom text-xl font-bold">Utilisateurs disponibles</h3>
            <p class="text-gray-400 mt-2">Démarrez une conversation selon vos permissions.</p>
        </div>
        <a href="/chat" class="bg-white text-black px-5 py-2.5 rounded-full font-bold hover:bg-gray-200 transition">
            Retour à la messagerie
        </a>
    </div>

    <div class="grid gap-4">
        @forelse($users as $chatUser)
            <div class="bg-dark-hover border border-gray-700 rounded-2xl p-5 flex justify-between items-center gap-4 flex-wrap">
                <div>
                    <h4 class="text-white font-bold text-lg">{{ $chatUser->name }}</h4>
                    <p class="text-gray-400 text-sm">{{ ucfirst($chatUser->role) }}</p>
                    <p class="text-gray-500 text-sm">{{ $chatUser->email }}</p>
                </div>

                <form action="{{ route('chat.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $chatUser->id }}">
                    <button type="submit" class="bg-cyan-custom text-black px-5 py-2.5 rounded-full font-bold hover:bg-cyan-400 transition">
                        Ouvrir la conversation
                    </button>
                </form>
            </div>
        @empty
            <div class="bg-dark-hover border border-dashed border-gray-700 rounded-2xl p-8 text-center text-gray-400">
                Aucun utilisateur disponible pour le moment.
            </div>
        @endforelse
    </div>
</div>
@endsection
