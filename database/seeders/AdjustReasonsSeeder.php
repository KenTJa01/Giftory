<?php

namespace Database\Seeders;

use App\Models\adjust_reasons;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdjustReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        adjust_reasons::firstOrCreate([
            'reason_code' => 'DMG',
        ], [
            'reason_desc' => 'Damage',
            'default_operator' => '-',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        adjust_reasons::firstOrCreate([
            'reason_code' => '+CORR',
        ], [
            'reason_desc' => 'Plus Qty Correction',
            'default_operator' => '+',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        adjust_reasons::firstOrCreate([
            'reason_code' => '-CORR',
        ], [
            'reason_desc' => 'Minus Qty Correction',
            'default_operator' => '-',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
