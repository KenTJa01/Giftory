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
        if (!Schema::hasColumn('adjustment_details', 'location_id')) {
            Schema::table('adjustment_details', function (Blueprint $table) {
                $table->bigInteger('location_id')->nullable();
                $table->foreign("location_id")->references("id")->on("locations");
            });
        }

        if (!Schema::hasColumn('adjustment_details', 'location_code')) {
            Schema::table('adjustment_details', function (Blueprint $table) {
                $table->string('location_code', 50)->nullable();
                $table->foreign("location_code")->references("location_code")->on("locations");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
