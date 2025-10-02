<?php

namespace Database\Seeders;

use App\Interfaces\InterfaceClass;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileMenu;
use App\Models\ProfilePermission;
use App\Models\SubMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** ------- Menu for super user ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::SUPERUSERPROFILE)->first()->id;
        $subMenus = SubMenu::orderBy('menu_id')->orderBy('id')->get();
        foreach ($subMenus as $subMenu) {
            ProfileMenu::firstOrCreate([
                'profile_id' => $profileId,
                'sub_menu_id' => $subMenu->id,
            ], [
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }

        /** ------- Menu from profile permissions ------- */
        ProfilePermission::where('profile_id', '!=', $profileId)->orderBy('profile_id')->orderBy('permission_id')
            ->chunkById(1000, function ($profilePermissions) {
                foreach ($profilePermissions as $profilePermission) {
                    $permission = Permission::where('id', $profilePermission->permission_id)->first();
                    ProfileMenu::firstOrCreate([
                        'profile_id' => $profilePermission->profile_id,
                        'sub_menu_id' => $permission->sub_menu_id,
                    ], [
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);
                }
            }, $column = 'id');
    }
}
