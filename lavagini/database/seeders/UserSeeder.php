<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un administrateur
        User::create([
            'name' => 'Admin Lavagini',
            'email' => 'admin@lavagini.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telephone' => '0612345678',
            'adresse' => '123 Rue Admin, Paris'
        ]);

        // Créer des laveurs
        User::create([
            'name' => 'Jean Laveur',
            'email' => 'jean@lavagini.com',
            'password' => Hash::make('password'),
            'role' => 'laveur',
            'telephone' => '0623456789',
            'adresse' => '45 Avenue des Laveurs, Paris'
        ]);

        User::create([
            'name' => 'Marie Nettoyage',
            'email' => 'marie@lavagini.com',
            'password' => Hash::make('password'),
            'role' => 'laveur',
            'telephone' => '0634567890',
            'adresse' => '78 Rue du Lavage, Lyon'
        ]);

        // Créer des clients
        User::create([
            'name' => 'Pierre Client',
            'email' => 'pierre@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '0645678901',
            'adresse' => '12 Rue des Clients, Paris',
            'type_client' => 'particulier'
        ]);

        User::create([
            'name' => 'Sophie Dupont',
            'email' => 'sophie@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '0656789012',
            'adresse' => '34 Boulevard Client, Marseille',
            'type_client' => 'particulier'
        ]);

        User::create([
            'name' => 'Agence Auto Plus',
            'email' => 'agence@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'telephone' => '0667890123',
            'adresse' => '56 Avenue des Agences, Lyon',
            'type_client' => 'agence'
        ]);
    }
}
