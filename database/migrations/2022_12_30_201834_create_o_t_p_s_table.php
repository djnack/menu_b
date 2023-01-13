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
        Schema::create('o_t_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('code', 6); // کد 6 رقمی
            $table->tinyInteger('try')->nullable(); // تلاش های ناموفق
            $table->boolean('active')->nullable(); // استفاده شدن کد
            $table->boolean('try_resend')->nullable(); // تعداد ارسال کد مجدد
            $table->timestamps();

            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('o_t_p_s');
    }
};
