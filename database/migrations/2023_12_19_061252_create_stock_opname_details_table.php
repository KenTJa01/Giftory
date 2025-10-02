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
        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('so_id')->constrained('stock_opname_headers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('catg_id')->constrained('product_categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('catg_code');
            $table->string('catg_desc');
            $table->float('before_quantity');
            $table->float('after_quantity')->nullable();
            $table->string('unit');
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
        Schema::dropIfExists('stock_opname_details');
    }
};
