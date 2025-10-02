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
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements("id");
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
            $table->float("avg_cost")->default(0);
            $table->integer("so_flag")->default(0);
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
        Schema::dropIfExists('stocks');
    }
};
