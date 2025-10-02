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
        Schema::create('adjustment_headers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('adj_no')->unique();
            $table->date('adj_date');

            $table->bigInteger("site_id")->nullable();
            $table->foreign("site_id")->references("id")->on("sites");

            $table->integer("site_code");
            $table->foreign("site_code")->references("site_code")->on("sites");

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
        Schema::dropIfExists('adjustment_headers');
    }
};
