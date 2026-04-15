<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZoneGeographique;

class ZoneGeographiqueSeeder extends Seeder
{
    public function run(): void
    {
        ZoneGeographique::create([
            'nom' => 'Paris Centre',
            'ville' => 'Paris',
            'code_postal' => '75001'
        ]);

        ZoneGeographique::create([
            'nom' => 'Paris Nord',
            'ville' => 'Paris',
            'code_postal' => '75018'
        ]);

        ZoneGeographique::create([
            'nom' => 'Lyon Centre',
            'ville' => 'Lyon',
            'code_postal' => '69001'
        ]);

        ZoneGeographique::create([
            'nom' => 'Marseille Centre',
            'ville' => 'Marseille',
            'code_postal' => '13001'
        ]);

        ZoneGeographique::create([
            'nom' => 'Toulouse Centre',
            'ville' => 'Toulouse',
            'code_postal' => '31000'
        ]);
    }
}
