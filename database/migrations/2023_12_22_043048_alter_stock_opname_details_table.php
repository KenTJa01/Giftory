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
        /** Add column */
        if (! Schema::hasColumn('stock_opname_details', 'variance_qty')) {
            Schema::table('stock_opname_details', function (Blueprint $table) {
                $table->float('variance_qty')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_opname_details', function (Blueprint $table) {
            $table->dropColumn('variance_qty');
        });
    }
};
