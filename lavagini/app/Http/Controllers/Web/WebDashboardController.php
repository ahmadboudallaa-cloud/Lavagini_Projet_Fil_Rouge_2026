<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Mission;
use App\Models\User;
use App\Models\ZoneGeographique;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class WebDashboardController extends Controller
{
    // Dashboard Client
    public function clientDashboard()
    {
        $user = Auth::user();
        
        $totalCommandes = Commande::where('client_id', $user->id)->count();
        $commandesEnCours = Commande::where('client_id', $user->id)
            ->whereIn('statut', ['demande', 'assignee', 'en_cours'])
            ->count();
        $commandesTerminees = Commande::where('client_id', $user->id)
            ->whereIn('statut', ['terminee', 'payee'])
            ->count();

        $dernieresCommandes = Commande::where('client_id', $user->id)
            ->with(['zone', 'mission.laveur'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $zones = ZoneGeographique::all();

        return view('client.dashboard', compact(
            'totalCommandes',
            'commandesEnCours',
            'commandesTerminees',
            'dernieresCommandes',
            'zones'
        ));
    }

    // Dashboard Laveur
    public function laveurDashboard()
    {
        $user = Auth::user();
        
        $totalMissions = Mission::where('laveur_id', $user->id)->count();
        $missionsEnCours = Mission::where('laveur_id', $user->id)
            ->whereIn('statut', ['assignee', 'en_cours'])
            ->count();
        
        $evaluations = Evaluation::where('laveur_id', $user->id)->get();
        $noteMoyenne = $evaluations->avg('note') ?? 0;

        $missions = Mission::where('laveur_id', $user->id)
            ->with(['commande.client', 'commande.zone'])
            ->orderBy('created_at', 'desc')
            ->get();

        $dernieresEvaluations = Evaluation::where('laveur_id', $user->id)
            ->with(['client', 'commande'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('laveur.dashboard', compact(
            'totalMissions',
            'missionsEnCours',
            'noteMoyenne',
            'missions',
            'dernieresEvaluations'
        ));
    }

    // Dashboard Admin
    public function adminDashboard()
    {
        $totalCommandes = Commande::count();
        $commandesEnAttente = Commande::where('statut', 'demande')->count();
        $totalClients = User::where('role', 'client')->count();
        $totalLaveurs = User::where('role', 'laveur')->count();
        $totalZones = ZoneGeographique::count();

        $commandes = Commande::with(['client', 'zone', 'mission.laveur'])
            ->orderBy('created_at', 'desc')
            ->get();

        $missions = Mission::with(['commande.client', 'laveur'])
            ->orderBy('created_at', 'desc')
            ->get();

        $clients = User::where('role', 'client')->get();
        $laveurs = User::where('role', 'laveur')->get();
        $zones = ZoneGeographique::all();

        return view('admin.dashboard', compact(
            'totalCommandes',
            'commandesEnAttente',
            'totalClients',
            'totalLaveurs',
            'totalZones',
            'commandes',
            'missions',
            'clients',
            'laveurs',
            'zones'
        ));
    }
}
