<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeightUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('weight_units')->insert([
            ['weight_unit' => 'Grams'],
            ['weight_unit' => 'Kg'],
            ['weight_unit' => 'Oz'],
            ['weight_unit' => 'Lbs']
        ]);
    }
}
