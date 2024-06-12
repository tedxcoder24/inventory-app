<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WeightUnitsTableSeeder::class,
            OperatorsTableSeeder::class,
            ItemTypesTableSeeder::class,
            StrainsTableSeeder::class,
            StatusesTableSeeder::class,
            ProductsTableSeeder::class,
            ColorsTableSeeder::class,
            ClaritiesTableSeeder::class,
            AppearancesTableSeeder::class,
            ConfigTableSeeder::class,
        ]);
    }
}
