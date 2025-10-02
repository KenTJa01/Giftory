<?php

namespace Database\Seeders;

use App\Interfaces\InterfaceClass;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfilePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfilePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** ------- Permission for regional marketing ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::REGIONALMARKETINGPROFILE)->first()->id;
        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_EDIT)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_RESET)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_MOVEMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::ADJUSTMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::ADJUSTMENT_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        /** ------- Permission for admin pusat ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::ADMINPROFILE)->first()->id;
        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_EDIT)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_USER_RESET)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::MASTER_SITE_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_LOCATION_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_LOCATION_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_LOCATION_EDIT)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_PRODUCT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_PRODUCT_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::MASTER_PRODUCT_EDIT)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_MOVEMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        /** ------- Permission for store manager ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::STOREMANAGERPROFILE)->first()->id;
        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_PRINT)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_APPROVAL)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_MOVEMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        /** ------- Permission for PIC gudang ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::PICGUDANGPROFILE)->first()->id;
        $permissionId = Permission::where('key', InterfaceClass::EXPENDING_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::EXPENDING_APPROVAL)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_PRINT)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_MOVEMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCKOPNAME_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
        
        $permissionId = Permission::where('key', InterfaceClass::STOCKOPNAME_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        /** ------- Permission for receiving ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::RECEIVINGPROFILE)->first()->id;
        $permissionId = Permission::where('key', InterfaceClass::RECEIVING_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::RECEIVING_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_PRINT)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_MOVEMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        /** ------- Permission for Operasional cabang ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::OPERASIONALCABPROFILE)->first()->id;
        $permissionId = Permission::where('key', InterfaceClass::EXPENDING_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::EXPENDING_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_MOVEMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCKOPNAME_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCKOPNAME_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        /** ------- Permission for Buyer cabang ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::BUYERCABPROFILE)->first()->id;
        $permissionId = Permission::where('key', InterfaceClass::MASTER_PRODUCT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::EXPENDING_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::EXPENDING_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::TRANSFER_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCK_MOVEMENT_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCKOPNAME_LIST)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);

        $permissionId = Permission::where('key', InterfaceClass::STOCKOPNAME_CREATE)->first()->id;
        ProfilePermission::firstOrCreate(['profile_id' => $profileId, 'permission_id' => $permissionId], ['created_by' => 1, 'updated_by' => 1]);
    }
}
