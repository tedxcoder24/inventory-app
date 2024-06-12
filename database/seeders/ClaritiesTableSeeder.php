<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClaritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clarities')->insert([
            ['clarity' => 'N/A'],
            ['clarity' => 'CLEAR'],
            ['clarity' => 'SOMEWHAT CLEAR'],
            ['clarity' => 'DARK'],
        ]);
    }
}
