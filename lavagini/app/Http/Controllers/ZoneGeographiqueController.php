<?php

namespace App\Http\Controllers;

use App\Models\ZoneGeographique;
use Illuminate\Http\Request;

class ZoneGeographiqueController extends Controller
{
    // Voir toutes les zones
    public function index()
    {
        $zones = ZoneGeographique::all();
        return response()->json($zones);
    }

    // Créer une zone (Admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10'
        ]);

        $zone = ZoneGeographique::create($validated);

        return response()->json([
            'message' => 'Zone créée avec succès',
            'zone' => $zone
        ], 201);
    }

    // Voir une zone spécifique
    public function show($id)
    {
        $zone = ZoneGeographique::findOrFail($id);
        return response()->json($zone);
    }

    // Mettre à jour une zone (Admin)
    public function update(Request $request, $id)
    {
        $zone = ZoneGeographique::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:255',
            'code_postal' => 'sometimes|string|max:10'
        ]);

        $zone->update($validated);

        return response()->json([
            'message' => 'Zone mise à jour',
            'zone' => $zone
        ]);
    }

    // Supprimer une zone (Admin)
    public function destroy($id)
    {
        $zone = ZoneGeographique::findOrFail($id);
        $zone->delete();

        return response()->json([
            'message' => 'Zone supprimée'
        ]);
    }
}
