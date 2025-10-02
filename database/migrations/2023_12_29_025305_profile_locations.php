<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profile_locations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");

            $table->bigInteger('location_id');
            $table->foreign("location_id")->references("id")->on("locations");

            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
