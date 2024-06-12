<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('strains')->insert([
            ['strain' => 'Strain1'],
            ['strain' => 'Strain2'],
            ['strain' => 'Strain3'],
        ]);
    }
}
