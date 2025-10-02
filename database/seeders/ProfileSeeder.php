<?php

namespace Database\Seeders;

use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::SUPERUSERPROFILE,
        ], [
            'profile_name' => 'Super User',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::STOREMANAGERPROFILE,
        ], [
            'profile_name' => 'Store Manager',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::REGIONALMARKETINGPROFILE,
        ], [
            'profile_name' => 'Regional Marketing',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        // Profile::firstOrCreate([
        //     'profile_code' => InterfaceClass::ITCABPROFILE,
        // ], [
        //     'profile_name' => 'IT Cabang',
        //     'flag' => 0,
        //     'created_by' => 0,
        //     'updated_by' => 0,
        // ]);

        /** 14-Mei-24, dinonaktifkan */
        // Profile::firstOrCreate([
        //     'profile_code' => InterfaceClass::FINANCEPROFILE,
        // ], [
        //     'profile_name' => 'Finance Cabang',
        //     'flag' => 0,
        //     'created_by' => 0,
        //     'updated_by' => 0,
        // ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::PICGUDANGPROFILE,
        ], [
            'profile_name' => 'PIC Gudang',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::ADMINPROFILE,
        ], [
            'profile_name' => 'Admin',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::RECEIVINGPROFILE,
        ], [
            'profile_name' => 'Receiving',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::OPERASIONALCABPROFILE,
        ], [
            'profile_name' => 'Operasional Cabang',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::BUYERCABPROFILE,
        ], [
            'profile_name' => 'Buyer Cabang',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        Profile::firstOrCreate([
            'profile_code' => InterfaceClass::SUPERUSERMARKETINGPROFILE,
        ], [
            'profile_name' => 'Super User Marketing',
            'flag' => 1,
            'created_by' => 0,
            'updated_by' => 0,
        ]);
    }
}
