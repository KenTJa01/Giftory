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
        Schema::create('transfer_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('trf_id');
            $table->foreign("trf_id")->references("id")->on("transfer_headers");

            $table->bigInteger('catg_id');
            $table->foreign("catg_id")->references("id")->on("product_categories");

            $table->string('catg_code', 100);
            $table->foreign("catg_code")->references("catg_code")->on("product_categories");

            $table->string('catg_desc');
            $table->float('unit_price');
            $table->float('quantity');
            $table->string('unit', 50);

            $table->bigInteger('from_location_id');
            $table->foreign("from_location_id")->references("id")->on("locations");

            $table->string('from_location_code', 50);
            $table->foreign("from_location_code")->references("location_code")->on("locations");

            $table->unique(['trf_id', 'catg_id', 'from_location_id']);
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
        Schema::dropIfExists('transfer_details');
    }
};
