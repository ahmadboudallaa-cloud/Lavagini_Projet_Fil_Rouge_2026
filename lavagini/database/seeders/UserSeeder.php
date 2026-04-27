<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@lavagini.com'],
            [
                'name' => 'Admin Lavagini',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'telephone' => '0612345678',
                'adresse' => '123 Boulevard Zerktouni, Casablanca',
            ]
        );
    }
}
