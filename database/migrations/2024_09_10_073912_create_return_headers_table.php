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
        Schema::create('return_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ret_no')->unique();
            $table->date('ret_date');

            $table->bigInteger("location_id")->nullable();
            $table->foreign("location_id")->references("id")->on("locations");

            $table->string("location_code")->nullable();
            $table->foreign("location_code")->references("location_code")->on("locations");

            $table->bigInteger("site_id");
            $table->foreign("site_id")->references("id")->on("sites");

            $table->integer("site_code");
            $table->foreign("site_code")->references("site_code")->on("sites");

            $table->string("supp_code", 50)->nullable();
            $table->foreign("supp_code")->references("supp_code")->on("suppliers");

            $table->string("supp_name")->nullable();

            $table->bigInteger('approved_by')->nullable();
            $table->foreign("approved_by")->references("id")->on("users");

            $table->timestamp("approved_date")->nullable();

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
        Schema::dropIfExists('return_headers');
    }
};
