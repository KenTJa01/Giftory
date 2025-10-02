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
        Schema::create('expending_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('req_id');
            $table->foreign("req_id")->references("id")->on("expending_headers");

            $table->bigInteger('catg_id');
            $table->foreign("catg_id")->references("id")->on("product_categories");

            $table->string('catg_code', 100);
            $table->foreign("catg_code")->references("catg_code")->on("product_categories");

            $table->string('catg_desc');
            $table->float('unit_price');
            $table->float('req_quantity');
            $table->string('unit', 50);
            $table->unique(['req_id', 'catg_id']);
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
        Schema::dropIfExists('expending_details');
    }
};
