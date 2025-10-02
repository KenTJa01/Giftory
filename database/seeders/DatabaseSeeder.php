<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LocationSeeder::class,
            UnitSeeder::class,
            // SiteSeeder::class,
            StatusSeeder::class,
            MenuSeeder::class,
            PermissionSeeder::class,
            ProfileSeeder::class,
            ProfileLocationSeeder::class,
            ProfilePermissionSeeder::class,
            ProfileMenuSeeder::class,
            MovementTypeSeeder::class,
            AdjustReasonsSeeder::class,
            UserSeeder::class,
        ]);
    }
}
