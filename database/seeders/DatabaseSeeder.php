<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {
        // Voeg rollen toe
        $this->call(RoleSeeder::class);

        // Voeg specifieke gebruikers toe
        $this->createSpecificUsers();
    }

    public function createSpecificUsers()
    {
        // Maak admin gebruiker aan
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@hoornhack.com',
            'password' => bcrypt('password')
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // Maak directeur gebruiker aan
        $directeur = User::create([
            'name' => 'Directeur',
            'email' => 'directeur@hoornhack.com',
            'password' => bcrypt('password')
        ]);
        $directeurRole = Role::where('name', 'directeur')->first();
        $directeur->roles()->attach($directeurRole);

        // Maak coordinator gebruiker aan
        $coordinator = User::create([
            'name' => 'Coordinator',
            'email' => 'coordinator@hoornhack.com',
            'password' => bcrypt('password')
        ]);
        $coordinatorRole = Role::where('name', 'coordinator')->first();
        $coordinator->roles()->attach($coordinatorRole);

        // Maak bewaker gebruiker aan
        $bewaker = User::create([
            'name' => 'Bewaker',
            'email' => 'bewaker@hoornhack.com',
            'password' => bcrypt('password')
        ]);
        $bewakerRole = Role::where('name', 'bewaker')->first();
        $bewaker->roles()->attach($bewakerRole);
    }
}

