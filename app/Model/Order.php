<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/2
 * Time: 16:03
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['order_number','address','phone', 'passport_id', 'goods_id', 'goods_number', 'order_price', 'passport_id_one', 'passport_id_two','status', 'created_time', 'pay_time','reward_one','reward_two'];
    public $timestamps = false;
    public static $ORDER_STATUS_TEXT = [
        0 => '待支付',
        1 => '已完成',
        2 => '卖家取消',
        3 => '买家取消',
    ];
    public static $TYPE_ZERO = 0;
    public static $TYPE_ONE = 1;
    public static $TYPE_TWO = 2;
    public static $TYPE_THREE = 3;

    public function passport()
    {
        return $this->belongsTo('App\Model\Passport', 'passport_id', 'passport_id');
    }

    public function passport_one()
    {
        return $this->belongsTo('App\Model\Passport', 'passport_id_one', 'passport_id');
    }

    public function passport_two()
    {
        return $this->belongsTo('App\Model\Passport', 'passport_id_two', 'passport_id');
    }


    public function goods()
    {
        return $this->belongsTo('App\Model\Good','goods_id','id')->withTrashed();
    }

    public function goodsInfo(){
        return $this->goods()->first();
    }

    public function pic(){
        return $this->goodsInfo()->albumFirst();
    }
}