<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/20
 * Time: 16:24
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class GoodsInventory extends Model
{
    protected $table = 'goods_inventory';
    public $timestamps = false;
    protected $fillable = [
        'goods_id',
        'type',
        'qty',
        'passport_id'
    ];

}