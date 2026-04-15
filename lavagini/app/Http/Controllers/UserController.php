<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Voir tous les utilisateurs (Admin)
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Voir les clients (Admin)
    public function clients()
    {
        $clients = User::where('role', 'client')->get();
        return response()->json($clients);
    }

    // Voir les laveurs (Admin)
    public function laveurs()
    {
        $laveurs = User::where('role', 'laveur')->get();
        return response()->json($laveurs);
    }

    // Créer un compte laveur (Admin)
    public function creerLaveur(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string'
        ]);

        $laveur = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'laveur',
            'telephone' => $request->telephone,
            'adresse' => $request->adresse
        ]);

        return response()->json([
            'message' => 'Compte laveur créé avec succès',
            'laveur' => $laveur
        ], 201);
    }

    // Voir un utilisateur spécifique
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string'
        ]);

        $user->update($request->only(['name', 'email', 'telephone', 'adresse']));

        return response()->json([
            'message' => 'Utilisateur mis à jour',
            'user' => $user
        ]);
    }

    // Supprimer un utilisateur (Admin)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé'
        ]);
    }
}
