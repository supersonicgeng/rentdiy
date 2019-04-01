<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerifyLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verify_log', function ($table) {
            $table->increments('id')->unsigned()->comment('短信id');

            $table->string('verify_text')->nullable()->comment('短信文本');
            $table->string('phone')->nullable('id')->comment('手机号');

            $table->dateTime('send_time')->nullable()->comment('发送时间');
            $table->tinyInteger('type')->nullable()->default(0)->unsigned()->comment('类型 0:注册验证');
            $table->tinyInteger('status')->nullable()->default(0)->unsigned()->comment('状态 0:未使用');
            $table->integer('code')->nullable()->unsigned()->comment('验证码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verify_log');
    }
}
