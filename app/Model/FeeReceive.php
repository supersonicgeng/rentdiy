<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FeeReceive extends Model
{
    protected $table    = 'fee_receive';
    protected $fillable = ['id','arrears_id','order_id','fee_type','pay_money','pay_method','pay_date','note','bank_check_id','created_at','updated_at','deleted_at'];
}
