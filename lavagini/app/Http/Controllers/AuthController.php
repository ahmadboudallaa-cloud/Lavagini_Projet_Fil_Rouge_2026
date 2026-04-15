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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'type_client' => 'nullable|in:particulier,agence'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'type_client' => $request->type_client
        ]);

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user
        ], 201);
    }

    // Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
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
