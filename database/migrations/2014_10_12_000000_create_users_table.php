<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            $table->string('user_agent')->unique();

            $table->timestamps();
        });

        Schema::create('identities', function (Blueprint $table) {
            $table->id();

            $table->string('identity')->unique();

            $table->timestamps();
        });

        // IP address
        Schema::create('i_p_s', function (Blueprint $table) {
            $table->id();

            $table->string('ip', 40)->unique();

            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('nikname')->nullable();
            $table->string('image')->nullable();

            $table->string('phone', 11)->unique();

            $table->string('password')->nullable();
            $table->boolean('is_block')->nullable();
            $table->boolean('active_otp')->nullable(); // فعال بودن شماره

            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // حذف سطحی
        });

      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('i_p_s');
        Schema::dropIfExists('identities');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('users');
    }
};