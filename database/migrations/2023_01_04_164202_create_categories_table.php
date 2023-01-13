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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('subset')->nullable(); // زیرمجموعه خود دسته بندی
            $table->string('name'); // عنوان دسته بندی
        });

        Schema::create('categoryables', function (Blueprint $table) {
            $table->integer("categoryables_id");
            $table->string("categoryables_type");

            $table->unsignedBigInteger('categories_id')->unsigned();
            $table->unsignedBigInteger('created_by_token_id');
            $table->unsignedBigInteger('deleted_by_token_id')->nullable();

            $table->foreign('categories_id')->references('id')->on('categories')->onDelete('cascade');
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
        Schema::dropIfExists('categoryables');
        Schema::dropIfExists('categories');
    }
};
