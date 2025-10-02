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
        Schema::create('stock_opname_catg_sums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('so_id');
            $table->foreign("so_id")->references("id")->on("stock_opname_headers");

            $table->bigInteger('catg_id');
            $table->foreign("catg_id")->references("id")->on("product_categories");

            $table->string('catg_code', 100);
            $table->foreign("catg_code")->references("catg_code")->on("product_categories");

            $table->string('catg_desc');
            $table->float('before_quantity');
            $table->float('after_quantity')->nullable();

            $table->bigInteger('unit');
            $table->foreign("unit")->references("id")->on("units");

            $table->unique(['so_id', 'catg_id']);
            $table->timestamps();
            $table->bigInteger("created_by");
            $table->bigInteger("updated_by");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_catg_sums');
    }
};
