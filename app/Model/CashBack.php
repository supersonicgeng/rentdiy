<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/20
 * Time: 16:52
 */

namespace App\Model;


use Illuminate\Database\Eloquent\Model;

class CashBack extends Model
{
    protected $table = 'cash_back';
    protected $fillable = [
        'order_id',
        'passport_id',
        'reward_passport_id',
        'money',
        'type'
    ];

    public function dealCreated_at()
    {
        return date('Y-m-d',strtotime($this->created_at));
    }

    public function passport()
    {
        return $this->belongsTo('App\Model\Passport', 'passport_id', 'passport_id');
    }

    public function passportFirst()
    {
        return $this->belongsTo('App\Model\Passport', 'passport_id', 'passport_id')->first();
    }

    public function rewardPassport()
    {
        return $this->belongsTo('App\Model\Passport', 'reward_passport_id', 'passport_id');
    }

    public function rewardPassportFirst()
    {
        return $this->belongsTo('App\Model\Passport', 'reward_passport_id', 'passport_id')->first();
    }

}