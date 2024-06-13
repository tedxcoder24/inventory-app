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
            [
                'item_type' => 'Jar',
                'weight_unit_id'=> 1,
            ],[
                'item_type' => 'Pack',
                'weight_unit_id'=> 2,
            ],[
                'item_type' => 'Case',
                'weight_unit_id'=> 3,
            ],[
                'item_type' => 'Bag',
                'weight_unit_id'=> 4,
            ],
        ]);
    }
}
