<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::firstOrCreate([
            'location_code' => 'GDG',
        ], [
            'location_name' => 'Gudang',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        /** 14-Mei-24, dinonaktifkan */
        // Location::firstOrCreate([
        //     'location_code' => 'FNA',
        // ], [
        //     'location_name' => 'Finance',
        //     'flag' => 0,
        //     'created_by' => 0,
        //     'updated_by' => 0,
        // ]);

        Location::firstOrCreate([
            'location_code' => 'MGR',
        ], [
            'location_name' => 'Store Manager',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);
    }
}
