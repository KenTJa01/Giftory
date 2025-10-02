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
        if (Schema::hasColumn('adjustment_headers', 'approved_by')) {
            Schema::table('adjustment_headers', function (Blueprint $table) {
                $table->dropColumn('approved_by');
            });
        }

        if (Schema::hasColumn('adjustment_headers', 'approved_date')) {
            Schema::table('adjustment_headers', function (Blueprint $table) {
                $table->dropColumn('approved_date');
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
