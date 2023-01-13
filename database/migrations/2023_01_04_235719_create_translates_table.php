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
        Schema::create('translates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('lang', 3);
            $table->text('text');
            $table->timestamps();
            $table->softDeletes(); // حذف سطحی

            $table->unsignedBigInteger('created_by_token_id');
            $table->unsignedBigInteger('deleted_by_token_id')->nullable();

            $table->foreign('created_by_token_id')->references('id')->on('personal_access_tokens')->onDelete('cascade');
            $table->foreign('deleted_by_token_id')->references('id')->on('personal_access_tokens')->onDelete('cascade');
        });

        Schema::create('translatables', function (Blueprint $table) {
            $table->unsignedBigInteger('translate_id')->unsigned();
            $table->integer("translatables_id");
            $table->string("translatables_type");

            $table->foreign('translate_id')->references('id')->on('translates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('translatables');
        Schema::dropIfExists('translates');
    }
};
