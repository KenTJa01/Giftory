<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::firstOrCreate([
            'unit_name' => 'PCS',
        ], [
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
