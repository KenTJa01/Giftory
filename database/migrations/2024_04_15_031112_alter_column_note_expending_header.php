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
        if (!Schema::hasColumn('expending_headers', 'note')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->string('note')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('expending_headers', 'note')) {
            Schema::table('expending_headers', function (Blueprint $table) {
                $table->dropColumn('note');
            });
        }
    }
};
