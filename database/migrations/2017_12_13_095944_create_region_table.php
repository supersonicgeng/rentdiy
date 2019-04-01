<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name',20)->comment('名称');
            $table->integer('parent_id')->default(0)->comment('父id');
            $table->string('short_name',20)->nullable()->comment('简称');
            $table->tinyInteger('level')->default(0)->comment('层级');
            $table->string('city_code',10)->nullable()->comment('城市编码');
            $table->string('zip_code',10)->nullable()->comment('邮政编码');
            $table->string('merger_name',100)->nullable()->comment('地址全称');
            $table->string('lng',30)->nullable()->comment('纬度');
            $table->string('lat',30)->nullable()->comment('经度');
            $table->string('full_pinyin')->nullable()->comment('地址拼音');
            $table->string('pinyin')->nullable()->comment('地址简拼');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
