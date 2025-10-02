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
        Schema::create('stock_opname_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('so_no')->unique();
            $table->date('so_date');
            $table->string('so_type', 50);

            $table->bigInteger("site_id");
            $table->foreign("site_id")->references("id")->on("sites");

            $table->integer("site_code");
            $table->foreign("site_code")->references("site_code")->on("sites");

            $table->float("total_items");
            $table->float("total_qty");
            $table->float("total_locations");

            $table->integer('flag');
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
        Schema::dropIfExists('stock_opname_headers');
    }
};
