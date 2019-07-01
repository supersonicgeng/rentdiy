<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderArrears extends Model
{
    protected $table    = 'order_arrears';
    protected $fillable = ['id','order_id','order_sn','user_id','landlord_user_id','landlord_name','items_name','describe','unit_price','number','subject_code','discount','tex','arrears_fee','invoice_sn','need_pay_fee','pay_fee','is_pay','District','TA','Region','note','rent_house_id','bank_check_id','created_at','updated_at','deleted_at'];
}
