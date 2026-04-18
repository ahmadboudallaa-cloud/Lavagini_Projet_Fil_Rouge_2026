<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commande;
use App\Models\Mission;
use App\Models\ZoneGeographique;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WebAdminController extends Controller
{
    // CRUD LAVEURS
    
    public function creerLaveur(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'laveur',
            'telephone' => $request->telephone,
            'adresse' => $request->adresse
        ]);

        return redirect('/admin/dashboard')->with('success', 'Laveur créé avec succès !');
    }

    public function modifierLaveur(Request $request, $id)
    {
        $laveur = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string'
        ]);

        $laveur->update([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse
        ]);

        if ($request->password) {
            $laveur->password = Hash::make($request->password);
            $laveur->save();
        }

        return redirect('/admin/dashboard')->with('success', 'Laveur modifié avec succès !');
    }

    public function supprimerLaveur($id)
    {
        $laveur = User::findOrFail($id);
        $laveur->delete();

        return redirect('/admin/dashboard')->with('success', 'Laveur supprimé avec succès !');
    }

    // CRUD CLIENTS

    public function modifierClient(Request $request, $id)
    {
        $client = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'type_client' => 'nullable|in:particulier,agence'
        ]);

        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'type_client' => $request->type_client
        ]);

        return redirect('/admin/dashboard')->with('success', 'Client modifié avec succès !');
    }

    public function supprimerClient($id)
    {
        $client = User::findOrFail($id);
        $client->delete();

        return redirect('/admin/dashboard')->with('success', 'Client supprimé avec succès !');
    }

    // CRUD ZONES

    public function creerZone(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10'
        ]);

        ZoneGeographique::create($request->all());

        return redirect('/admin/dashboard')->with('success', 'Zone créée avec succès !');
    }

    public function modifierZone(Request $request, $id)
    {
        $zone = ZoneGeographique::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10'
        ]);

        $zone->update($request->all());

        return redirect('/admin/dashboard')->with('success', 'Zone modifiée avec succès !');
    }

    public function supprimerZone($id)
    {
        $zone = ZoneGeographique::findOrFail($id);
        $zone->delete();

        return redirect('/admin/dashboard')->with('success', 'Zone supprimée avec succès !');
    }

    // ASSIGNER UNE MISSION

    public function assignerMission(Request $request)
    {
        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'laveur_id' => 'required|exists:users,id'
        ]);

        $mission = Mission::create([
            'commande_id' => $request->commande_id,
            'laveur_id' => $request->laveur_id,
            'statut' => 'assignee'
        ]);

        // Mettre à jour le statut de la commande
        $commande = Commande::find($request->commande_id);
        $commande->statut = 'assignee';
        $commande->save();

        // Notification au laveur
        Notification::create([
            'user_id' => $request->laveur_id,
            'titre' => 'Nouvelle mission',
            'message' => 'Une nouvelle mission vous a été assignée',
            'type' => 'mission'
        ]);

        // Notification au client
        Notification::create([
            'user_id' => $commande->client_id,
            'titre' => 'Mission assignée',
            'message' => 'Un laveur a été assigné à votre commande',
            'type' => 'mission'
        ]);

        return redirect('/admin/dashboard')->with('success', 'Mission assignée avec succès !');
    }

    // VOIR DETAILS COMMANDE

    public function voirCommande($id)
    {
        $commande = Commande::with(['client', 'zone', 'mission.laveur', 'paiement', 'facture', 'evaluation'])
            ->findOrFail($id);

        $laveurs = User::where('role', 'laveur')->get();

        return view('admin.commande-detail', compact('commande', 'laveurs'));
    }
}
