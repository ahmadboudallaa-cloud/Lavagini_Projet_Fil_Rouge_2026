<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarif;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        Tarif::create([
            'type_service' => 'lavage_standard',
            'prix_unitaire' => 100.00,
            'description' => 'Lavage extérieur standard par véhicule'
        ]);

        Tarif::create([
            'type_service' => 'lavage_complet',
            'prix_unitaire' => 150.00,
            'description' => 'Lavage extérieur + intérieur par véhicule'
        ]);

        Tarif::create([
            'type_service' => 'lavage_premium',
            'prix_unitaire' => 250.00,
            'description' => 'Lavage complet + cirage par véhicule'
        ]);
    }
}
