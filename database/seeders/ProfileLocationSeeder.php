<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Interfaces\InterfaceClass;
use App\Models\Location;
use App\Models\ProfileLocation;
use Illuminate\Database\Seeder;

class ProfileLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** ------- Location for PIC gudang ------- */
        $profileId = Profile::where('profile_code', InterfaceClass::PICGUDANGPROFILE)->first()->id;
        $locationId = Location::where('location_code', 'MGR')->first()->id;
        ProfileLocation::firstOrCreate(['profile_id' => $profileId, 'location_id' => $locationId], ['created_by' => 1, 'updated_by' => 1]);

        $locationId = Location::where('location_code', 'GDG')->first()->id;
        ProfileLocation::firstOrCreate(['profile_id' => $profileId, 'location_id' => $locationId], ['created_by' => 1, 'updated_by' => 1]);
    }
}
