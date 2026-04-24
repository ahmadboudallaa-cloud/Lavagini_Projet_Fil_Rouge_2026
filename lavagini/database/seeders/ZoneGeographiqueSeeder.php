<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZoneGeographique;

class ZoneGeographiqueSeeder extends Seeder
{
    public function run(): void
    {
        ZoneGeographique::create([
            'nom' => 'Casablanca Centre',
            'ville' => 'Casablanca',
            'code_postal' => '20000'
        ]);

        ZoneGeographique::create([
            'nom' => 'Rabat Centre',
            'ville' => 'Rabat',
            'code_postal' => '10000'
        ]);

        ZoneGeographique::create([
            'nom' => 'Marrakech Centre',
            'ville' => 'Marrakech',
            'code_postal' => '40000'
        ]);

        ZoneGeographique::create([
            'nom' => 'Fes Centre',
            'ville' => 'Fes',
            'code_postal' => '30000'
        ]);

        ZoneGeographique::create([
            'nom' => 'Tanger Centre',
            'ville' => 'Tanger',
            'code_postal' => '90000'
        ]);
    }
}
