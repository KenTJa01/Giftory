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
        Schema::create('return_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ret_id');
            $table->foreign("ret_id")->references("id")->on("return_headers");

            $table->bigInteger('catg_id');
            $table->foreign("catg_id")->references("id")->on("product_categories");
            $table->string('catg_code', 100);
            $table->foreign("catg_code")->references("catg_code")->on("product_categories");

            $table->bigInteger("location_id")->nullable();
            $table->foreign("location_id")->references("id")->on("locations");

            $table->string("location_code")->nullable();
            $table->foreign("location_code")->references("location_code")->on("locations");

            $table->string('catg_desc');
            $table->float('unit_price');
            $table->float('quantity');
            $table->string('unit', 50);

            $table->unique(['ret_id', 'catg_id']);

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
        Schema::dropIfExists('return_details');
    }
};
