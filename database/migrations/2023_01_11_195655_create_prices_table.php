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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('price');
        });

        Schema::create('priceables', function (Blueprint $table) {
            $table->integer("priceables_id");
            $table->string("priceables_type");
            $table->string('detail')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->unsignedBigInteger('price_id')->unsigned();
            $table->unsignedBigInteger('created_by_token_id');
            $table->unsignedBigInteger('deleted_by_token_id')->nullable();

            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
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
        Schema::dropIfExists('priceables');
        Schema::dropIfExists('prices');
    }
};
