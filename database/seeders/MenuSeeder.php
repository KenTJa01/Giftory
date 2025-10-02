<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** ------------ DATA MASTER ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Data Master',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'master-user',
        ], [
            'sub_menu_name' => 'Users',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'master-profile',
        ], [
            'sub_menu_name' => 'Profiles',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'master-site',
        ], [
            'sub_menu_name' => 'Sites',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'master-location',
        ], [
            'sub_menu_name' => 'Locations',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'master-product-category',
        ], [
            'sub_menu_name' => 'Product Categories',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'master-unit',
        ], [
            'sub_menu_name' => 'Units',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'master-supplier',
        ], [
            'sub_menu_name' => 'Suppliers',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        /** ------------ RECEIVING ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Receiving',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'list-receiving',
        ], [
            'sub_menu_name' => 'List',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'form-receiving',
        ], [
            'sub_menu_name' => 'Form',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        /** ------------ EXPENDING ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Expending',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'list-expending',
        ], [
            'sub_menu_name' => 'List',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'form-expending',
        ], [
            'sub_menu_name' => 'Form',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        /** ------------ TRANSFER ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Transfer',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'list-transfer',
        ], [
            'sub_menu_name' => 'List',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'form-transfer',
        ], [
            'sub_menu_name' => 'Form',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        /** ------------ STOCK ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Stock',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'list-stock',
        ], [
            'sub_menu_name' => 'List',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'movement-stock',
        ], [
            'sub_menu_name' => 'Movement',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        /** ------------ STOCK OPNAME ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Stock Opname',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'list-stock-opname',
        ], [
            'sub_menu_name' => 'List',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'form-stock-opname',
        ], [
            'sub_menu_name' => 'Form',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        /** ------------ ADJUSTMENTS ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Adjustments',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'list-adjustments',
        ], [
            'sub_menu_name' => 'List',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'form-adjustments',
        ], [
            'sub_menu_name' => 'Form',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        /** ------------ RETURN ------------ */
        $menuId = Menu::firstOrCreate([
            'menu_name' => 'Return',
        ], [
            'menu_url' => '-',
            'flag' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'list-return',
        ], [
            'sub_menu_name' => 'List',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        SubMenu::firstOrCreate([
            'menu_id' => $menuId->id,
            'sub_menu_url' => 'form-return',
        ], [
            'sub_menu_name' => 'Form',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
