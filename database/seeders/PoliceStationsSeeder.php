<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliceStationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('police_stations')->insert([
            ['name' => 'Central Police Station', 'location' => 'Downtown', 'phone' => '1234567890', 'email' => 'central@police.gov', 'address' => '123 Main St', 'is_active' => true],
            ['name' => 'North Police Station', 'location' => 'Northside', 'phone' => '0987654321', 'email' => 'north@police.gov', 'address' => '456 North St', 'is_active' => true],
            ['name' => 'East Police Station', 'location' => 'Eastside', 'phone' => '1122334455', 'email' => 'east@police.gov', 'address' => '789 East St', 'is_active' => true],
        ]);
    }
}
