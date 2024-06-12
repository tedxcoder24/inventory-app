<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            ['status' => 'IN'],
            ['status' => 'OUT'],
            ['status' => 'ISOLATOR'],
            ['status' => 'CENTRIFUGE'],
            ['status' => 'PRODUCTION'],
        ]);
    }
}
