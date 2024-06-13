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
                'abbreviation'=> 'g',
            ],
            [
                'weight_unit' => 'Kilograms', 
                'abbreviation' => 'kg'
            ],
            [
                'weight_unit' => 'Ounce', 
                'abbreviation' => 'oz'
            ],
            [
                'weight_unit' => 'Pounds', 
                'abbreviation' => 'lbs'
            ],
        ]);
    }
}
