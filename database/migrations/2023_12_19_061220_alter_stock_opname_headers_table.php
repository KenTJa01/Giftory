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
        /** Removed column */
        if (Schema::hasColumn('stock_opname_headers', 'total_locations')) {
            Schema::table('stock_opname_headers', function (Blueprint $table) {
                $table->dropColumn('total_locations');
            });
        }

        /** Add column */
        if (! Schema::hasColumn('stock_opname_headers', 'location_id')) {
            Schema::table('stock_opname_headers', function (Blueprint $table) {
                $table->foreignId('location_id')->constrained('locations')->cascadeOnUpdate()->cascadeOnDelete();
            });
        }
        if (! Schema::hasColumn('stock_opname_headers', 'location_code')) {
            Schema::table('stock_opname_headers', function (Blueprint $table) {
                $table->string('location_code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opname_headers', function (Blueprint $table) {
            $table->dropColumn('location_id');
            $table->dropColumn('location_code');
        });
    }
};
