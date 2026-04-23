<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'telephone' => 'nullable|string',
                'adresse' => 'nullable|string',
                'type_client' => 'nullable|in:particulier,agence',
                'password' => 'nullable|min:6|confirmed',
                'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'name.required' => 'Le nom est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être valide.',
                'email.unique' => 'Cet email est déjà utilisé.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
                'photo_profile.image' => 'Le fichier doit être une image.',
                'photo_profile.mimes' => 'L\'image doit être au format JPG, PNG ou GIF.',
                'photo_profile.max' => 'L\'image ne doit pas dépasser 2MB.'
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

            // Gestion de la photo de profil
            if ($request->hasFile('photo_profile')) {
                // Supprimer l'ancienne photo si elle existe
                if ($user->photo_profile) {
                    $oldPath = public_path('uploads/profiles/' . $user->photo_profile);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                // Créer le dossier s'il n'existe pas
                $uploadPath = public_path('uploads/profiles');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Enregistrer la nouvelle photo
                $file = $request->file('photo_profile');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);
                $user->photo_profile = $filename;
            }

            $user->save();

            return redirect('/profil')->with('success', 'Profil mis à jour avec succès !');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect('/profil')
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect('/profil')->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function supprimerPhoto()
    {
        try {
            $user = Auth::user();

            if ($user->photo_profile) {
                $photoPath = public_path('uploads/profiles/' . $user->photo_profile);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
                $user->photo_profile = null;
                $user->save();
                
                return redirect('/profil')->with('success', 'Photo supprimée avec succès !');
            }
            
            return redirect('/profil')->with('error', 'Aucune photo à supprimer.');
            
        } catch (\Exception $e) {
            return redirect('/profil')->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

}
