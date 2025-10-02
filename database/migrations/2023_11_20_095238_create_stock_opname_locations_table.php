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
        Schema::create('stock_opname_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('so_id');
            $table->foreign("so_id")->references("id")->on("stock_opname_headers");

            $table->bigInteger("site_id");
            $table->foreign("site_id")->references("id")->on("sites");

            $table->integer("site_code");
            $table->foreign("site_code")->references("site_code")->on("sites");

            $table->bigInteger("location_id");
            $table->foreign("location_id")->references("id")->on("locations");

            $table->string("location_code",100);
            $table->foreign("location_code")->references("location_code")->on("locations");

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
        Schema::dropIfExists('stock_opname_locations');
    }
};
