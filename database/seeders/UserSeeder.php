<?php

namespace Database\Seeders;

use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use App\Models\Site;
use App\Models\User;
use App\Models\UserSite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** ------- Create initial users ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::SUPERUSERPROFILE)->first()->id;

        User::firstOrCreate([
            'username' => 'admin'
        ], [
            'password' => Hash::make('12345'),
            'name' => 'Admin',
            'is_active' => 1,
            'profile_id' => $profileId,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        User::firstOrCreate([
            'username' => '23080012'
        ], [
            'password' => Hash::make('12345'),
            'name' => 'Kenken Tjahyadi',
            'is_active' => 1,
            'profile_id' => $profileId,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        User::firstOrCreate([
            'username' => '23080014'
        ], [
            'password' => Hash::make('12345'),
            'name' => 'Stefanus Kristiyanto Hendrawan',
            'is_active' => 1,
            'profile_id' => $profileId,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        User::firstOrCreate([
            'username' => '14040036'
        ], [
            'password' => Hash::make('12345'),
            'name' => 'Yucky',
            'is_active' => 1,
            'profile_id' => $profileId,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        User::firstOrCreate([
            'username' => '18120001'
        ], [
            'password' => Hash::make('12345'),
            'name' => 'Ivan Tjahja Wiguna',
            'is_active' => 1,
            'profile_id' => $profileId,
            'created_by' => 0,
            'updated_by' => 0,
        ]);

        /** ------- Assign user sites for initial users ------- */
        $sites = Site::orderBy('site_code')->get();
        foreach ($sites as $site) {
            $users = User::orderBy('id')->get();
            foreach ($users as $user) {
                UserSite::firstOrCreate([
                    'user_id' => $user->id,
                    'site_id' => $site->id,
                ], [
                    'site_code' => $site->site_code,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }
        }
    }
}
