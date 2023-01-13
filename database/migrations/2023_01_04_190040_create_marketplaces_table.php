<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplaces', function (Blueprint $table) {
            $table->id();
            $table->boolean('name')->nullable(); // کمکی هست
            $table->string('slug')->unique();
            $table->boolean('slogan')->nullable(); // کمکی هست
            $table->boolean('img_brand')->nullable(); // کمکی هست
            $table->boolean('img_abl')->nullable(); // کمکی هست
            $table->boolean('img_bg')->nullable(); // کمکی هست
            $table->boolean('is_block')->nullable();
            $table->timestamps();
            $table->softDeletes(); // حذف سطحی

            $table->unsignedBigInteger('created_by_token_id');
            $table->unsignedBigInteger('deleted_by_token_id')->nullable();

            $table->foreign('created_by_token_id')->references('id')->on('personal_access_tokens')->onDelete('cascade');
            $table->foreign('deleted_by_token_id')->references('id')->on('personal_access_tokens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplaces');
    }
};
