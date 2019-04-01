<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_passports', function (Blueprint $table) {
            $table->increments('passport_id')->comment('通行证');
            $table->string('openid',30)->unique()->nullable()->comment('微信openid');
            $table->string('unionid',30)->nullable()->comment('微信unionid');
            $table->integer('groupid')->default(0)->comment('微信groupid');
            $table->string('nickname',50)->comment('昵称');
            $table->string('headimgurl',200)->nullable()->comment('头像');
            $table->tinyInteger('sex')->default(0)->comment('性别:1男2女');
            $table->tinyInteger('subscribe')->default(0)->comment('关注');
            $table->string('phone',11)->unique()->nullable()->comment('手机号');
            $table->string('email',30)->unique()->nullable()->comment('邮箱');
            $table->string('country',30)->nullable()->comment('国家');
            $table->string('province',30)->nullable()->comment('省');
            $table->string('city',30)->nullable()->comment('市');
            $table->string('county',30)->nullable()->comment('区');
            $table->date('birthday')->nullable()->comment('生日');
            $table->integer('share')->comment('分享次数');
            $table->integer('best_score')->comment('个人最高分');
            $table->integer('add_up_score')->comment('累计得分');
            $table->integer('recommend_score')->comment('分享得分');
            $table->integer('total_score')->comment('总分');
            $table->timestamp('subscribe_time')->nullable()->comment('关注时间');
            $table->timestamp('unsubscribe_time')->nullable()->comment('取消关注时间');
            $table->string('token',32)->nullable()->comment('登录token');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_passports');
    }
}
