<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebAuthController;
use App\Http\Controllers\Web\WebCommandeController;
use App\Http\Controllers\Web\WebDashboardController;
use App\Http\Controllers\Web\WebAdminController;
use App\Http\Controllers\Web\WebMissionController;

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
    
    // Routes pour les notifications
    Route::put('/notifications/{id}/lire', [\App\Http\Controllers\NotificationController::class, 'marquerCommeLue']);
    
    // Routes Client
    Route::middleware('role:client')->group(function () {
        Route::get('/client/dashboard', [WebDashboardController::class, 'clientDashboard']);
        Route::post('/commandes', [WebCommandeController::class, 'store']);
        Route::get('/client/commandes', [WebCommandeController::class, 'mesCommandes']);
        Route::get('/client/commandes/{id}', [WebCommandeController::class, 'show']);
        Route::post('/client/evaluations/{commandeId}', [WebCommandeController::class, 'creerEvaluation']);
        Route::get('/client/factures', [WebCommandeController::class, 'mesFactures']);
    });
    
    // Routes Laveur
    Route::middleware('role:laveur')->group(function () {
        Route::get('/laveur/dashboard', [WebDashboardController::class, 'laveurDashboard']);
        Route::post('/laveur/missions/{id}/demarrer', [WebMissionController::class, 'demarrer']);
        Route::get('/laveur/missions/{id}/terminer', [WebMissionController::class, 'showTerminer']);
        Route::post('/laveur/missions/{id}/terminer', [WebMissionController::class, 'terminer']);
        Route::get('/laveur/missions/{id}', [WebMissionController::class, 'show']);
    });
    
    // Routes Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [WebDashboardController::class, 'adminDashboard']);
        
        // CRUD Laveurs
        Route::post('/admin/laveurs', [WebAdminController::class, 'creerLaveur']);
        Route::post('/admin/laveurs/{id}', [WebAdminController::class, 'modifierLaveur']);
        Route::delete('/admin/laveurs/{id}', [WebAdminController::class, 'supprimerLaveur']);
        
        // CRUD Clients
        Route::post('/admin/clients/{id}', [WebAdminController::class, 'modifierClient']);
        Route::delete('/admin/clients/{id}', [WebAdminController::class, 'supprimerClient']);
        
        // CRUD Zones
        Route::post('/admin/zones', [WebAdminController::class, 'creerZone']);
        Route::post('/admin/zones/{id}', [WebAdminController::class, 'modifierZone']);
        Route::delete('/admin/zones/{id}', [WebAdminController::class, 'supprimerZone']);
        
        // Gestion des commandes
        Route::post('/admin/missions/assigner', [WebAdminController::class, 'assignerMission']);
        Route::get('/admin/commandes/{id}', [WebAdminController::class, 'voirCommande']);
        Route::delete('/admin/commandes/{id}', [WebAdminController::class, 'supprimerCommande']);
    });
});
