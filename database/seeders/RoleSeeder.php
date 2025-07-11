<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Voeg de rollen toe, maak alleen aan als ze nog niet bestaan
        Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator met volledige toegang']
        );
        Role::firstOrCreate(
            ['name' => 'directeur'],
            ['description' => 'Directeur']
        );
        Role::firstOrCreate(
            ['name' => 'coordinator'],
            ['description' => 'Coördinator bewakers']
        );
        Role::firstOrCreate(
            ['name' => 'bewaker'],
            ['description' => 'Bewaker']
        );
        Role::firstOrCreate(
            ['name' => 'bezoeker'],
            ['description' => 'Bezoeker (familielid of andere bezoeker)']
        );
    }
}