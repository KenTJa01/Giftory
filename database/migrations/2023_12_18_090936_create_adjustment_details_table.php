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
        Schema::create('adjustment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('adj_id');
            $table->foreign("adj_id")->references("id")->on("adjustment_headers");

            $table->bigInteger('catg_id');
            $table->foreign("catg_id")->references("id")->on("product_categories");

            $table->string('catg_code', 100);
            $table->foreign("catg_code")->references("catg_code")->on("product_categories");

            $table->string('catg_desc');
            $table->integer('adj_qty');
            $table->integer('stock_before_adj');
            $table->integer('stock_after_adj');
            $table->string('unit', 50);

            $table->string('reason_code');
            $table->foreign("reason_code")->references("reason_code")->on("adjust_reasons");

            $table->unique(['adj_id', 'catg_id']);
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
        Schema::dropIfExists('adjustment_details');
    }
};
