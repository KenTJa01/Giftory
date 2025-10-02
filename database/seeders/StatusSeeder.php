<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::firstOrCreate([
            'module' => 'transfer',
            'flag_value' => '1',
        ], [
            'flag_desc' => 'Pending Approve',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'transfer',
            'flag_value' => '2',
        ], [
            'flag_desc' => 'Approved',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'transfer',
            'flag_value' => '3',
        ], [
            'flag_desc' => 'Received',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'transfer',
            'flag_value' => '8',
        ], [
            'flag_desc' => 'Rejected',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'receiving',
            'flag_value' => '0',
        ], [
            'flag_desc' => 'Draft',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'receiving',
            'flag_value' => '1',
        ], [
            'flag_desc' => 'Finished',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'expending',
            'flag_value' => '1',
        ], [
            'flag_desc' => 'Pending Approve',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'expending',
            'flag_value' => '2',
        ], [
            'flag_desc' => 'Finished',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'expending',
            'flag_value' => '8',
        ], [
            'flag_desc' => 'Rejected',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'stock_opname',
            'flag_value' => '0',
        ], [
            'flag_desc' => 'Draft',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'stock_opname',
            'flag_value' => '1',
        ], [
            'flag_desc' => 'Freeze',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'stock_opname',
            'flag_value' => '2',
        ], [
            'flag_desc' => 'Stock Upload',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'stock_opname',
            'flag_value' => '3',
        ], [
            'flag_desc' => 'Finished',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'stock_opname',
            'flag_value' => '9',
        ], [
            'flag_desc' => 'Cancel',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'adjustment',
            'flag_value' => '1',
        ], [
            'flag_desc' => 'Finished',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'return',
            'flag_value' => '1',
        ], [
            'flag_desc' => 'Finished',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'return',
            'flag_value' => '2',
        ], [
            'flag_desc' => 'Pending Approve',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        Status::firstOrCreate([
            'module' => 'return',
            'flag_value' => '8',
        ], [
            'flag_desc' => 'Rejected',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
