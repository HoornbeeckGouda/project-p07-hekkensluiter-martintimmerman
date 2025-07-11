<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cell;
class CellsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    Cell::create([
        'afdeling' => 'A1',
        'celnummer' => '101',
    ]);

    Cell::create([
        'afdeling' => 'B2',
        'celnummer' => '202',
    ]);

    Cell::create([
        'afdeling' => 'C3',
        'celnummer' => '303',
    ]);
}
}
