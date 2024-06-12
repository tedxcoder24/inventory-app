<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('operators')->insert([
            ['operator' => 'Comfy'],
            ['operator' => 'Kade'],
            ['operator' => 'Bubba'],
            ['operator' => 'Edmund'],
            ['operator' => 'Spencer'],
            ['operator' => 'Ron'],
            ['operator' => 'Tyler'],
            ['operator' => 'Willie'],
        ]);
    }
}
