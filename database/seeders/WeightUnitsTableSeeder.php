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
            [
                'weight_unit' => 'Grams',
                'abbreviation' => 'g',
                'convert_to_grams' => 1,
            ],
            [
                'weight_unit' => 'Kilograms',
                'abbreviation' => 'kg',
                'convert_to_grams' => 1000,
            ],
            [
                'weight_unit' => 'Ounce',
                'abbreviation' => 'oz',
                'convert_to_grams' => 28.3495,
            ],
            [
                'weight_unit' => 'Pounds',
                'abbreviation' => 'lbs',
                'convert_to_grams' => 453.592,
            ],
        ]);
    }
}
