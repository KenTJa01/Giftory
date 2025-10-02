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
        Schema::create('transfer_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trf_no')->unique();
            $table->date('trf_date');

            $table->bigInteger("origin_site_id");
            $table->foreign("origin_site_id")->references("id")->on("sites");

            $table->integer("origin_site_code");
            $table->foreign("origin_site_code")->references("site_code")->on("sites");

            $table->bigInteger("destination_site_id");
            $table->foreign("destination_site_id")->references("id")->on("sites");

            $table->integer("destination_site_code");
            $table->foreign("destination_site_code")->references("site_code")->on("sites");

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
        Schema::dropIfExists('transfer_headers');
    }
};
