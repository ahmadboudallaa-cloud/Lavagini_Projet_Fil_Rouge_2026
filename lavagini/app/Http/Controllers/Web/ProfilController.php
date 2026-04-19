<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profil.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'type_client' => 'nullable|in:particulier,agence',
            'password' => 'nullable|min:6|confirmed',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->adresse = $request->adresse;

        if ($user->role === 'client' && $request->type_client) {
            $user->type_client = $request->type_client;
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo_profile')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
                Storage::disk('public')->delete($user->photo_profile);
            }

            // Enregistrer la nouvelle photo
            $path = $request->file('photo_profile')->store('profiles', 'public');
            $user->photo_profile = $path;
        }

        $user->save();

        return redirect('/profil')->with('success', 'Profil mis à jour avec succès !');
    }

    public function supprimerPhoto()
    {
        $user = Auth::user();

        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
            $user->photo_profile = null;
            $user->save();
        }

        return redirect('/profil')->with('success', 'Photo supprimée avec succès !');
    }
}
