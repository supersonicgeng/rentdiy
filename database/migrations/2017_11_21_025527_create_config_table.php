<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_config', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',20)->comment('配置名称');
            $table->string('code',20)->comment('配置唯一标识');
            $table->enum('type',['checkbox','radio','text','select','file','password'])->comment('配置类型');
            $table->text('value')->nullable()->comment('配置的值');
            $table->string('note',100)->nullable()->comment('配置描述');
            $table->integer('show_order')->default(100)->comment('排序');
            $table->string('group',50)->comment('所属模块');
            $table->tinyInteger('status')->default(1)->comment('启用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_config');
    }
}
