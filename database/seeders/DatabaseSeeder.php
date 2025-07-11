<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(RoleSeeder::class);
        
        $this->call(PermissionSeeder::class);

        $this->createSpecificUsers();

        $this->call(TwoFactorSeeder::class);
    }

    public function createSpecificUsers()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hoornhack.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->hasRole('admin')) {
            $admin->roles()->sync([$adminRole->id]);
        }

        $directeur = User::firstOrCreate(
            ['email' => 'directeur@hoornhack.com'],
            [
                'name' => 'Directeur',
                'password' => Hash::make('password'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $directeurRole = Role::where('name', 'directeur')->first();
        if ($directeurRole && !$directeur->hasRole('directeur')) {
            $directeur->roles()->sync([$directeurRole->id]);
        }

        $coordinator = User::firstOrCreate(
            ['email' => 'coordinator@hoornhack.com'],
            [
                'name' => 'Coordinator',
                'password' => Hash::make('password'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $coordinatorRole = Role::where('name', 'coordinator')->first();
        if ($coordinatorRole && !$coordinator->hasRole('coordinator')) {
            $coordinator->roles()->sync([$coordinatorRole->id]);
        }

        $bewaker = User::firstOrCreate(
            ['email' => 'bewaker@hoornhack.com'],
            [
                'name' => 'Bewaker',
                'password' => Hash::make('password'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $bewakerRole = Role::where('name', 'bewaker')->first();
        if ($bewakerRole && !$bewaker->hasRole('bewaker')) {
            $bewaker->roles()->sync([$bewakerRole->id]);
        }
    }
}