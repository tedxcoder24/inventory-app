<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('item_types')->insert([
            ['item_type' => 'Jar'],
            ['item_type' => 'Pack'],
            ['item_type' => 'Case'],
            ['item_type' => 'Bag'],
        ]);
    }
}
