<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passport extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'user_passports';
    protected $fillable = ['openid', 'identity', 'total_reward', 'unionid', 'reward', 'groupid', 'nickname', 'headimgurl', 'sex', 'subscribe', 'country', 'province', 'city', 'subscribe_time', 'unsubscribe_time', 'created_at', 'updated_at', 'level_id', 'status', 'id_card_img1', 'id_card_img2', 'id_card_img3', 'token','reffer_id'];
    protected $primaryKey = 'passport_id';

    public static $SEX_TXT = [
        0 => '未知',
        1 => '男',
        2 => '女'
    ];
    public static $SEX_UNKNOW = 0;
    public static $SEX_MALE = 1;
    public static $SEX_FEMALE = 2;

    public static $SUBSCRIPT_TXT = [
        1 => '关注',
        0 => '未关注'
    ];
    public static $SUBSCRIPT_ON = 0;
    public static $SUBSCRIPT_OFF = 1;

    public static $STATUS_TEXT = [
        1 => '已认证',
        0 => '未认证'
    ];
    public static $STATUS_ON = 0;
    public static $STATUS_OFF = 1;

    public static $ID_TEXT = [
        1 => '代理人',
        0 => '普通用户'
    ];
    public static $ID_NORMAL = 0;
    public static $ID_VIP = 1;

    public static $ORIGIN = 23850;

    public function incrementScore($value = 0)
    {
        return parent::increment('score', $value);
    }

    public function incrementReward($value = 0)
    {
        parent::increment('total_reward', $value);
        return parent::increment('reward', $value);
    }

    public function decrementReward($value = 0)
    {
        return parent::decrement('reward', $value);
    }

    public function incrementRewardW($value = 0)
    {
        return parent::increment('reward', $value);
    }

    //买家订单
    public function order()
    {
        return $this->hasMany('App\Model\Order', 'passport_id', 'id');
    }

    //一级分销订单
    public function order_one()
    {
        return $this->hasMany('App\Model\Order', 'passport_id_one', 'passport_id');
    }

    //二级分销订单
    public function order_two()
    {
        return $this->hasMany('App\Model\Order', 'passport_id_two', 'passport_id');
    }

//    //三级分销订单
//    public function order_three()
//    {
//        return $this->hasMany('App\Model\Order', 'passport_id_three', 'id');
//    }

    //上级
    public function reffer()
    {
        return $this->hasOne('App\Model\Passport', 'passport_id', 'reffer_id');
    }

    public function refferFirst()
    {
        return $this->hasOne('App\Model\Passport', 'passport_id', 'reffer_id')->first();
    }

    //累计消费
    public function orderSum()
    {
        return $this->hasMany('App\Model\Order', 'passport_id', 'passport_id')->where('status', '=', 1)->sum('order_price');
    }

    //为上级累计提供收益
    public function rewardToPrev()
    {
        $prev = $this->hasOne('App\Model\Passport', 'passport_id', 'reffer_id')->first();
        $model = new CashBack();
        return $model->where('reward_passport_id',$prev->passport_id)->where('passport_id',$this->passport_id)->sum('money');
    }
}
