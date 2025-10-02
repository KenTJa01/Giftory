<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Site::firstOrCreate([
            'site_code' => 70102,
        ], [
            'store_code' => 'S60',
            'site_description' => 'YOGYA SUNDA 60',
            'site_type' => 'CAB',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Site::firstOrCreate([
            'site_code' => 70103,
        ], [
            'store_code' => 'SLK',
            'site_description' => 'YOGYA SILIWANGI',
            'site_type' => 'CAB',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Site::firstOrCreate([
            'site_code' => 70104,
        ], [
            'store_code' => 'TSK',
            'site_description' => 'YOGYA TASIK',
            'site_type' => 'CAB',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Site::firstOrCreate([
            'site_code' => 70106,
        ], [
            'store_code' => 'YJ8',
            'site_description' => 'YOGYA JUNCTION 8',
            'site_type' => 'CAB',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Site::firstOrCreate([
            'site_code' => 70107,
        ], [
            'store_code' => 'YDP',
            'site_description' => 'YOGYA DEPOK',
            'site_type' => 'CAB',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
