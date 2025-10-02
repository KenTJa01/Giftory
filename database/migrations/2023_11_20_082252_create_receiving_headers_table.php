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
        Schema::create('receiving_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rec_no')->unique();
            $table->date('rec_date');

            $table->bigInteger("origin_site_id")->nullable();
            $table->foreign("origin_site_id")->references("id")->on("sites");

            $table->integer("origin_site_code");
            $table->foreign("origin_site_code")->references("site_code")->on("sites");

            $table->bigInteger("destination_site_id");
            $table->foreign("destination_site_id")->references("id")->on("sites");

            $table->integer("destination_site_code");
            $table->foreign("destination_site_code")->references("site_code")->on("sites");

            $table->string("supp_code", 50);
            $table->foreign("supp_code")->references("supp_code")->on("suppliers");

            $table->string("supp_name");

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
        Schema::dropIfExists('receiving_headers');
    }
};
