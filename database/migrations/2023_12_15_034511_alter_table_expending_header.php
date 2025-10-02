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
        if (! Schema::hasColumn('expending_headers', 'location_id')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->bigInteger("location_id")->default('1');
                $table->foreign("location_id")->references("id")->on("locations");
            });
        }
        if (! Schema::hasColumn('expending_headers', 'location_code')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->string("location_code")->default('GDG');
                $table->foreign("location_code")->references("location_code")->on("locations");
            });
        }

        /** Now drop default */
        Schema::table('expending_headers', function (Blueprint $table) {
            $table->bigInteger('location_id')->nullable(false)->default(null)->change();
        });
        Schema::table('expending_headers', function (Blueprint $table) {
            $table->string('location_code')->nullable(false)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('expending_headers', 'location_id')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->dropColumn('location_id');
            });
        }
        if (Schema::hasColumn('expending_headers', 'location_code')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->dropColumn('location_code');
            });
        }
    }
};
