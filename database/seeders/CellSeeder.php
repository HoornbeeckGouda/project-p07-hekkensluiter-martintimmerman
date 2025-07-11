<?php

namespace Database\Seeders;

use App\Models\Cell; // This connects to your Cell model
use Illuminate\Database\Seeder;

class CellSeeder extends Seeder
{
    public function run()
    {
        for ($number = 100; $number <= 112; $number++) {
            Cell::create([
                'afdeling' => 'A',
                'celnummer' => $number 
            ]);
        }

        for ($number = 210; $number <= 215; $number++) {
            Cell::create([
                'afdeling' => 'B', 
                'celnummer' => $number 
            ]);
        }

        for ($number = 336; $number <= 364; $number++) {
            Cell::create([
                'afdeling' => 'C', 
                'celnummer' => $number 
            ]);
        }
    }
}