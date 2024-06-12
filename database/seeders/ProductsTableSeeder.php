<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['product' => 'CRUMBLE'],
            ['product' => 'WAX'],
            ['product' => 'TRIM'],
            ['product' => 'FLOWER'],
            ['product' => 'DISTILLATE'],
            ['product' => 'ISOLATE'],
            ['product' => 'TERPS'],
            ['product' => 'DIAMONDS'],
        ]);
    }
}
