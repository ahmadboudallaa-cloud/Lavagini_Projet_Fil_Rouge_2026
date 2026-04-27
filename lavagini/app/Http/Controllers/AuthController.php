<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Inscription d'un client
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'type_client' => 'nullable|in:particulier,agence'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'telephone' => $validated['telephone'] ?? null,
            'adresse' => $validated['adresse'] ?? null,
            'type_client' => $validated['type_client'] ?? null
        ]);

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user
        ], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'message' => 'Connexion réussie',
                'user' => $user
            ]);
        }

        return response()->json([
            'message' => 'Email ou mot de passe incorrect'
        ], 401);
    }

    // Déconnexion
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Déconnexion réussie'
        ]);
    }

    // Obtenir l'utilisateur connecté
    public function me()
    {
        return response()->json(Auth::user());
    }
}
