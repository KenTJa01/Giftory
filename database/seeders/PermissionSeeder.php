<?php

namespace Database\Seeders;

use App\Interfaces\InterfaceClass;
use App\Models\Permission;
use App\Models\SubMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** ---------- TRANSACTIONS ---------- */
        /** List permissions for sub menu transfer list */
        $subMenuId = SubMenu::where('sub_menu_url', 'list-transfer')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::TRANSFER_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::TRANSFER_PRINT]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::TRANSFER_APPROVAL]);

        /** List permissions for sub menu transfer form */
        $subMenuId = SubMenu::where('sub_menu_url', 'form-transfer')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::TRANSFER_CREATE]);

        /** List permissions for sub menu receiving list */
        $subMenuId = SubMenu::where('sub_menu_url', 'list-receiving')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::RECEIVING_LIST]);

        /** List permissions for sub menu receiving form */
        $subMenuId = SubMenu::where('sub_menu_url', 'form-receiving')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::RECEIVING_CREATE]);

        /** List permissions for sub menu expending list */
        $subMenuId = SubMenu::where('sub_menu_url', 'list-expending')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::EXPENDING_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::EXPENDING_APPROVAL]);

        /** List permissions for sub menu expending form */
        $subMenuId = SubMenu::where('sub_menu_url', 'form-expending')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::EXPENDING_CREATE]);

        /** List permissions for sub menu adjustment list */
        $subMenuId = SubMenu::where('sub_menu_url', 'list-adjustments')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::ADJUSTMENT_LIST]);

        /** List permissions for sub menu adjustment form */
        $subMenuId = SubMenu::where('sub_menu_url', 'form-adjustments')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::ADJUSTMENT_CREATE]);

        /** List permissions for sub menu stock opname list */
        $subMenuId = SubMenu::where('sub_menu_url', 'list-stock-opname')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::STOCKOPNAME_LIST]);

        /** List permissions for sub menu stock opname form */
        $subMenuId = SubMenu::where('sub_menu_url', 'form-stock-opname')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::STOCKOPNAME_CREATE]);

        /** List permissions for sub menu stock list */
        $subMenuId = SubMenu::where('sub_menu_url', 'list-stock')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::STOCK_LIST]);

        /** List permissions for sub menu stock movement list */
        $subMenuId = SubMenu::where('sub_menu_url', 'movement-stock')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::STOCK_MOVEMENT_LIST]);

        /** ---------- DATA MASTER ---------- */
        /** List permissions for sub menu master users */
        $subMenuId = SubMenu::where('sub_menu_url', 'master-user')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_USER_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_USER_CREATE]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_USER_EDIT]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_USER_RESET]);

        /** List permissions for sub menu master profiles */
        $subMenuId = SubMenu::where('sub_menu_url', 'master-profile')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_PROFILE_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_PROFILE_CREATE]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_PROFILE_EDIT]);

        /** List permissions for sub menu master sites */
        $subMenuId = SubMenu::where('sub_menu_url', 'master-site')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_SITE_LIST]);

        /** List permissions for sub menu master locations */
        $subMenuId = SubMenu::where('sub_menu_url', 'master-location')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_LOCATION_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_LOCATION_CREATE]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_LOCATION_EDIT]);

        /** List permissions for sub menu master product categories */
        $subMenuId = SubMenu::where('sub_menu_url', 'master-product-category')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_PRODUCT_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_PRODUCT_CREATE]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_PRODUCT_EDIT]);

        /** List permissions for sub menu master units */
        $subMenuId = SubMenu::where('sub_menu_url', 'master-unit')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_UNIT_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_UNIT_CREATE]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_UNIT_EDIT]);

        /** List permissions for sub menu master suppliers */
        $subMenuId = SubMenu::where('sub_menu_url', 'master-supplier')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_SUPPLIER_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_SUPPLIER_CREATE]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::MASTER_SUPPLIER_EDIT]);

        /** List permissions for sub menu return list */
        $subMenuId = SubMenu::where('sub_menu_url', 'list-return')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::RETURN_LIST]);
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::RETURN_APPROVAL]);

        /** List permissions for sub menu return form */
        $subMenuId = SubMenu::where('sub_menu_url', 'form-return')->first()->id;
        Permission::firstOrCreate(['sub_menu_id' => $subMenuId, 'key' => InterfaceClass::RETURN_CREATE]);
    }
}
