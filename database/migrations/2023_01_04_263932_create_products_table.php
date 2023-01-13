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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->boolean('name')->nullable(); //کمکی
            $table->string('slug');
            $table->boolean('description')->nullable(); //کمکی
            $table->boolean('price')->nullable(); //کمکی
            $table->boolean('discount')->nullable(); //کمکی
            $table->boolean('image')->nullable(); //کمکی
            $table->integer('count')->nullable();
            $table->boolean('publish')->nullable();
            $table->timestamp('publish_start')->nullable();
            $table->timestamp('publish_stop')->nullable();
            $table->boolean('is_block')->nullable();
            $table->boolean('recycle_bin')->nullable();
            $table->timestamps();
            $table->softDeletes(); // حذف سطحی

            $table->unsignedBigInteger('marketplace_id');
            $table->unsignedBigInteger('created_by_token_id');
            $table->unsignedBigInteger('deleted_by_token_id')->nullable();

            $table->foreign('marketplace_id')->references('id')->on('marketplaces')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
};
