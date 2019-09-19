<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $guarded = [];

    protected $table = 'withdraw';

    /***
     * 用户信息
     */
    public function customer()
    {
        return $this->belongsTo(Customer::Class, 'customer_id', 'id');
    }

    /***
     * 审核管理员信息
     */
    public function admin(){
        return $this->belongsTo('App\User','admin_id','id');
    }
}
