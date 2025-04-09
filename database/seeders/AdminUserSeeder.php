<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@hekkensluiter.nl',
            'password' => Hash::make('password'),
        ]);

        $role = Role::where('name', 'directeur')->first();
        
        if ($role) {
            $user->roles()->attach($role->id);
        }
    }
}