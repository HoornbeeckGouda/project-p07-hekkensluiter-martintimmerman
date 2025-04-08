<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Voeg de rollen toe
        Role::create(['name' => 'admin', 'description' => 'Administrator met volledige toegang']);
        Role::create(['name' => 'directeur', 'description' => 'Directeur']);
        Role::create(['name' => 'coordinator', 'description' => 'Coördinator bewakers']);
        Role::create(['name' => 'bewaker', 'description' => 'Bewaker']);
    }
}
