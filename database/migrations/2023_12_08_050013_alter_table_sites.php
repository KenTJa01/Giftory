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
        if (! Schema::hasColumn('sites', 'site_type')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->string('site_type')->default('CAB');
            });
        }

        /** Now drop default */
        Schema::table('sites', function (Blueprint $table) {
            $table->string('site_type')->nullable(false)->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sites', 'site_type')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->dropColumn('site_type');
            });
        }
    }
};
