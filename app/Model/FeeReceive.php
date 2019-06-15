<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FeeReceive extends Model
{
    protected $table    = 'fee_receive';
    protected $fillable = ['id','arrears_id','pay_money','pay_method','pay_date','note','created_at','updated_at','deleted_at'];
}
