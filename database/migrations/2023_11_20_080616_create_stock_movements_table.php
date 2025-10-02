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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->date("mov_date");

            $table->bigInteger("site_id");
            $table->foreign("site_id")->references("id")->on("sites");

            $table->integer("site_code");
            $table->foreign("site_code")->references("site_code")->on("sites");

            $table->bigInteger("location_id");
            $table->foreign("location_id")->references("id")->on("locations");

            $table->string("location_code", 100);
            $table->foreign("location_code")->references("location_code")->on("locations");

            $table->bigInteger("catg_id");
            $table->foreign("catg_id")->references("id")->on("product_categories");

            $table->string("catg_code", 100);
            $table->foreign("catg_code")->references("catg_code")->on("product_categories");

            $table->float("quantity");
            $table->string("unit", 50);
            $table->string("mov_code");
            $table->foreign("mov_code")->references("mov_code")->on("movement_types");

            // $table->unique(['mov_date', 'site_id', 'location_id', 'catg_id', 'mov_code']);

            $table->float("purch_price")->default(0);
            $table->float("sales_price")->default(0);
            $table->string('ref_no');
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
        Schema::dropIfExists('stock_movements');
    }
};
