<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class PassportReward extends Model
{
    protected $table = 'passport_rewards';
    protected $fillable = [
        'passport_id',
        'apply_number',
        'type',
        'account',
        'username',
        'apply_money',
        'rate',
        'money',
        'status',
        'note',
        'apply_time'
    ];
    public static $STATUS_TEXT = [
        0=>[
            'color'=>'warning',
            'text'=>'未审核'
        ],
        1=>[
            'color'=>'primary',
            'text'=>'已打款'
        ],
        2=>[
            'color'=>'danger',
            'text'=>'已驳回'
        ]
    ];
    public static $TYPE_TEXT = [
        1=>'支付宝',
        2=>'微信'
    ];
    public static $UNDO = 0;
    public static $PASS = 1;
    public static $DENY = 2;
    public function passport()
    {
        return $this->belongsTo('App\Model\Passport', 'passport_id', 'passport_id');
    }

    public static function createApplyNumber(){
        $dateTime = date("YmdHis"); // 格式化当前时间戳
        $reqNoKey = 'requestTimes_' . $dateTime; // 设置redis键值，每秒钟的请求次数
        $reqNo = Redis::INCR($reqNoKey); // 将redis值加1
        Redis::EXPIRE($reqNoKey, 5);// 设置redis过期时间,避免垃圾数据过多
        $reqNo = 100000 + $reqNo; // 补齐订单号长度
        $orderNo = $dateTime  . $reqNo;
        return $orderNo;
    }
}
