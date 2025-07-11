<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TwoFactorSeeder extends Seeder
{
    public function run(): void
    {
        // Update alle bestaande users om 2FA aan te zetten
        DB::table('users')
            ->where('two_factor_enabled', false)
            ->update(['two_factor_enabled' => true]);
        
        $this->command->info('2FA is aangezet voor alle gebruikers.');
    }
}