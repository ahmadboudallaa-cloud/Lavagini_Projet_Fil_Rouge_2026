@extends(auth()->user()->role === 'client' ? 'layouts.client' : (auth()->user()->role === 'laveur' ? 'layouts.laveur' : 'layouts.admin'))

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('content')

<div class="bg-dark-card rounded-[30px] p-8 shadow-xl">
    <h3 class="text-cyan-custom text-xl font-bold mb-8">Mon Profil</h3>
    
    <form action="/profil" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="flex items-center space-x-6 mb-8">
            <div class="w-24 h-24 bg-gray-600 rounded-full flex items-center justify-center overflow-hidden">
                @if($user->photo_profile)
                    <img src="{{ asset('uploads/profiles/' . $user->photo_profile) }}" class="w-full h-full object-cover" id="profilePreview">
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
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none" required>
            </div>
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none">
            </div>
            <div>
                <label class="block text-gray-300 font-semibold mb-2">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse', $user->adresse) }}" class="w-full px-4 py-3 bg-dark-hover border border-gray-600 rounded-xl text-white focus:border-cyan-custom focus:outline-none">
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
            <a href="{{ auth()->user()->role === 'client' ? '/client/dashboard' : (auth()->user()->role === 'laveur' ? '/laveur/dashboard' : '/admin/dashboard') }}" class="px-6 py-3 bg-gray-600 text-white rounded-full font-bold hover:bg-gray-700">Annuler</a>
            <button type="submit" class="px-6 py-3 bg-cyan-custom text-black rounded-full font-bold hover:bg-cyan-400">Enregistrer les modifications</button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
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
</script>
@endsection
