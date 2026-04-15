<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\WebCommandeController;
use App\Http\Controllers\Web\WebDashboardController;

// Page d'accueil
Route::get('/', function () {
    return view('home');
});

// Routes d'authentification
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegister']);
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware('auth')->group(function () {
    
    // Redirection vers le dashboard selon le rôle
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'laveur') {
            return redirect('/laveur/dashboard');
        } else {
            return redirect('/client/dashboard');
        }
    });
    
    // Routes Client
    Route::middleware('role:client')->group(function () {
        Route::get('/client/dashboard', [WebDashboardController::class, 'clientDashboard']);
        Route::post('/commandes', [WebCommandeController::class, 'store']);
        Route::get('/commandes/mes-commandes', [WebCommandeController::class, 'mesCommandes']);
        Route::get('/commandes/{id}', [WebCommandeController::class, 'show']);
    });
    
    // Routes Laveur
    Route::middleware('role:laveur')->group(function () {
        Route::get('/laveur/dashboard', [WebDashboardController::class, 'laveurDashboard']);
    });
    
    // Routes Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [WebDashboardController::class, 'adminDashboard']);
    });
});
