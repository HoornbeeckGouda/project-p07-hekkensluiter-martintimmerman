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
        
        // BELANGRIJK: Voeg permissions toe VOOR users
        $this->call(PermissionSeeder::class);

        // Voeg specifieke gebruikers toe
        $this->createSpecificUsers();
    }

    public function createSpecificUsers()
    {
        // Maak admin gebruiker aan
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@hoornhack.com',
            'password' => bcrypt('password'),
            'is_active' => true // Voeg dit toe
        ]);
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->roles()->attach($adminRole);
        }

        // Maak directeur gebruiker aan
        $directeur = User::create([
            'name' => 'Directeur',
            'email' => 'directeur@hoornhack.com',
            'password' => bcrypt('password'),
            'is_active' => true
        ]);
        $directeurRole = Role::where('name', 'directeur')->first();
        if ($directeurRole) {
            $directeur->roles()->attach($directeurRole);
        }

        // Maak coordinator gebruiker aan
        $coordinator = User::create([
            'name' => 'Coordinator',
            'email' => 'coordinator@hoornhack.com',
            'password' => bcrypt('password'),
            'is_active' => true
        ]);
        $coordinatorRole = Role::where('name', 'coordinator')->first();
        if ($coordinatorRole) {
            $coordinator->roles()->attach($coordinatorRole);
        }

        // Maak bewaker gebruiker aan
        $bewaker = User::create([
            'name' => 'Bewaker',
            'email' => 'bewaker@hoornhack.com',
            'password' => bcrypt('password'),
            'is_active' => true
        ]);
        $bewakerRole = Role::where('name', 'bewaker')->first();
        if ($bewakerRole) {
            $bewaker->roles()->attach($bewakerRole);
        }
    }
}