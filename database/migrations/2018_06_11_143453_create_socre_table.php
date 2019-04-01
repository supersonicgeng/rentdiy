<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_score', function (Blueprint $table) {
            $table->increments('id')->comment('主键');
            $table->integer('passport_id')->comment('通行证');
            $table->integer('share_passport_id')->comment('发送分享链接的通行证');
            $table->integer('current_score')->commnet('当局得分');
            $table->integer('best_score')->comment('个人最高分');
            $table->integer('add_up_score')->comment('累计得分');
            $table->integer('recommend_score')->comment('分享得分');
            $table->integer('total_score')->comment('总分');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_score');
    }
}
