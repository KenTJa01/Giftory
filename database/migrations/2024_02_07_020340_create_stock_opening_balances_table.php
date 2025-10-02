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
        Schema::create('stock_opening_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('filename');
            $table->integer('site_code');
            $table->string('catg_code', 100);
            $table->string('catg_desc');
            $table->string('location_code', 100);
            $table->float('qty');
            $table->integer('flag')->default(0);
            $table->timestamps();

            $table->index('site_code');
            $table->index('catg_code');
            $table->index('location_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opening_balances');
    }
};
