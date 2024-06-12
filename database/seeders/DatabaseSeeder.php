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
            ConfigTableSeeder::class,
            WeightUnitsTableSeeder::class,
            OperatorsTableSeeder::class,
            ItemTypesTableSeeder::class,
            StrainsTableSeeder::class,
            StatusesTableSeeder::class,
            ProductsTableSeeder::class,
            ColorsTableSeeder::class,
            ClaritiesTableSeeder::class,
            AppearancesTableSeeder::class,
            RolesTableSeeder::class,
        ]);

        \App\Models\User::create([
            "name"=> "admin",
            "email"=> "admin@gmail.com",
            "password"=> bcrypt("123123123"),
        ]);
        \App\Models\User::where("email", "admin@gmail.com")->first()->assignRole("admin");
        \App\Models\User::factory(10)->create();
    }
}
