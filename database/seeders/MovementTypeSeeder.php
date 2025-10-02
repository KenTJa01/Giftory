<?php

namespace Database\Seeders;

use App\Interfaces\InterfaceClass;
use App\Models\MovementType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::TRANSFERIN_MOVEMENT,
        ], [
            'mov_name' => 'Transfer In',
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::TRANSFEROUT_MOVEMENT,
        ], [
            'mov_name' => 'Transfer Out',
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::RECEIVING_MOVEMENT,
        ], [
            'mov_name' => 'Receiving',
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::EXPENDING_MOVEMENT,
        ], [
            'mov_name' => 'Expending',
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::ADJUSTMENT_MOVEMENT,
        ], [
            'mov_name' => 'Adjustment',
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::STOCKOPNAME_MOVEMENT,
        ], [
            'mov_name' => 'Stock Opname',
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::OPENINGBALANCE_MOVEMENT,
        ], [
            'mov_name' => 'Opening Balance',
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        MovementType::firstOrCreate([
            'mov_code' => InterfaceClass::RETURN_MOVEMENT,
        ], [
            'mov_name' => 'Return',
            'created_by' => 0,
            'updated_by' => 0,
        ]);
    }
}
