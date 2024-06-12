<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppearancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appearances')->insert([
            ['appearance' => 'N/A'],
            ['appearance' => 'Very Wet'],
            ['appearance' => 'Wet'],
            ['appearance' => 'Slightly Wet'],
            ['appearance' => 'Dry'],
            ['appearance' => 'Rocky'],
            ['appearance' => 'Big Rocks'],
        ]);
    }
}
