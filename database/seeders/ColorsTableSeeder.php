<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('colors')->insert([
            ['color' => ''],
            ['color' => 'LIGHT'],
            ['color' => 'YELLOW'],
            ['color' => 'CREAM'],
            ['color' => 'CARAMEL'],
            ['color' => 'DARK'],
        ]);
    }
}
