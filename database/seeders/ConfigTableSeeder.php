<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('config')->insert([
            'last_serial_number' => 0,
            'serial_port' => 'COM1',
            'label_printer' => 'PRINTER1',
            'report_printer' => 'PRINTER2',
            'image_directory' => '/images',
        ]);
    }
}
