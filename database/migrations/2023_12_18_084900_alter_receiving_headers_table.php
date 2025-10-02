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
        if (Schema::hasColumn('receiving_headers', 'origin_site_code')) {
            Schema::table('receiving_headers', function (Blueprint $table) {
                $table->integer('origin_site_code')->nullable()->change();
            });
        }
        if (Schema::hasColumn('receiving_headers', 'supp_code')) {
            Schema::table('receiving_headers', function (Blueprint $table) {
                $table->string('supp_code')->nullable()->change();
            });
        }
        if (Schema::hasColumn('receiving_headers', 'supp_name')) {
            Schema::table('receiving_headers', function (Blueprint $table) {
                $table->string('supp_name')->nullable()->change();
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
