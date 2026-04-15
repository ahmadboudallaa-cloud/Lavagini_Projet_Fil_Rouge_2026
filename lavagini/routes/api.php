<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ZoneGeographiqueController;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes pour les zones géographiques (public)
Route::get('/zones', [ZoneGeographiqueController::class, 'index']);
Route::get('/zones/{id}', [ZoneGeographiqueController::class, 'show']);

// Routes protégées (nécessitent une authentification)
Route::middleware('auth:sanctum')->group(function () {
    
    // Routes d'authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Routes pour les commandes
    Route::post('/commandes', [CommandeController::class, 'store'])->middleware('role:client');
    Route::get('/commandes/mes-commandes', [CommandeController::class, 'mesCommandes'])->middleware('role:client');
    Route::get('/commandes', [CommandeController::class, 'index'])->middleware('role:admin');
    Route::get('/commandes/{id}', [CommandeController::class, 'show']);
    Route::put('/commandes/{id}/statut', [CommandeController::class, 'updateStatut'])->middleware('role:admin');

    // Routes pour les missions
    Route::post('/missions/assigner', [MissionController::class, 'assigner'])->middleware('role:admin');
    Route::get('/missions/mes-missions', [MissionController::class, 'mesMissions'])->middleware('role:laveur');
    Route::get('/missions', [MissionController::class, 'index'])->middleware('role:admin');
    Route::put('/missions/{id}/demarrer', [MissionController::class, 'demarrer'])->middleware('role:laveur');
    Route::put('/missions/{id}/terminer', [MissionController::class, 'terminer'])->middleware('role:laveur');

    // Routes pour les utilisateurs
    Route::get('/users', [UserController::class, 'index'])->middleware('role:admin');
    Route::get('/users/clients', [UserController::class, 'clients'])->middleware('role:admin');
    Route::get('/users/laveurs', [UserController::class, 'laveurs'])->middleware('role:admin');
    Route::post('/users/laveurs', [UserController::class, 'creerLaveur'])->middleware('role:admin');
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('role:admin');

    // Routes pour les paiements
    Route::post('/paiements', [PaiementController::class, 'store']);
    Route::put('/paiements/{id}/valider', [PaiementController::class, 'valider'])->middleware('role:admin');
    Route::get('/paiements', [PaiementController::class, 'index'])->middleware('role:admin');
    Route::get('/paiements/{id}', [PaiementController::class, 'show']);

    // Routes pour les évaluations
    Route::post('/evaluations', [EvaluationController::class, 'store'])->middleware('role:client');
    Route::get('/evaluations/laveur/{laveur_id}', [EvaluationController::class, 'evaluationsLaveur']);
    Route::get('/evaluations', [EvaluationController::class, 'index'])->middleware('role:admin');

    // Routes pour les notifications
    Route::get('/notifications', [NotificationController::class, 'mesNotifications']);
    Route::get('/notifications/non-lues', [NotificationController::class, 'nonLues']);
    Route::put('/notifications/{id}/lire', [NotificationController::class, 'marquerCommeLue']);
    Route::put('/notifications/tout-lire', [NotificationController::class, 'marquerToutCommeLu']);

    // Routes pour les zones géographiques (Admin)
    Route::post('/zones', [ZoneGeographiqueController::class, 'store'])->middleware('role:admin');
    Route::put('/zones/{id}', [ZoneGeographiqueController::class, 'update'])->middleware('role:admin');
    Route::delete('/zones/{id}', [ZoneGeographiqueController::class, 'destroy'])->middleware('role:admin');
});
