<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email',70)->unique();
            $table->string('password');
            $table->string('phone',11)->nullable();
            $table->integer('passport_id')->default(0);
            $table->rememberToken();
            $table->timestamps();
            $table->tinyInteger('is_super')->nullable()->unsigned()->comment('0:超级管理员 1:普通管理员');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
