<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Page d'accueil
Route::get('/', function () {
    return view('home');
});

// Routes d'authentification
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
});

// Routes protégées
Route::middleware('auth')->group(function () {
    
    // Redirection vers le dashboard selon le rôle
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return view('admin.dashboard');
        } elseif ($user->role === 'laveur') {
            return view('laveur.dashboard');
        } else {
            return view('client.dashboard');
        }
    });
    
    // Dashboard Client
    Route::get('/client/dashboard', function () {
        return view('client.dashboard');
    })->middleware('role:client');
    
    // Dashboard Laveur
    Route::get('/laveur/dashboard', function () {
        return view('laveur.dashboard');
    })->middleware('role:laveur');
    
    // Dashboard Admin
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin');
});
