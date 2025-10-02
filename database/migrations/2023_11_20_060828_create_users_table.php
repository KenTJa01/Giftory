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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('password');
            $table->string('name');
            $table->integer('is_active')->default(1);
            $table->bigInteger('profile_id');
            $table->foreign("profile_id")->references("id")->on("profiles");
            $table->bigInteger('homebase_site_id')->nullable();
            $table->foreign("homebase_site_id")->references("id")->on("sites");
            $table->integer('homebase_site_code')->nullable();
            $table->foreign("homebase_site_code")->references("site_code")->on("sites");
            $table->string('remember_token')->nullable();
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
        Schema::dropIfExists('users');
    }
};
