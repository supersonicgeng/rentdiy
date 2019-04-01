<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/17
 * Time: 14:27
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class PassportStore extends Model
{
    protected $table = 'passport_store';
    protected $fillable = ['goods_id', 'passport_id', 'type', 'qty'];

    public function goods()
    {
        return $this->belongsTo('App\Model\Good','goods_id','id');
    }

    public function goodsInfo(){
        return $this->goods()->first();
    }

    public function pic(){
        return $this->goodsInfo()->albumFirst();
    }
}