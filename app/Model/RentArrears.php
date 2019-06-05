<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentArrears extends Model
{
    protected $table    = 'rent_arrears';
    protected $fillable = ['id','contract_id','contract_sn','user_id','rent_house_id','tenement_id','tenement_name','tenement_email','tenement_phone','arrears_type','property_name',
        'effect_date','arrears_fee','rent_circle','rent_times','is_pay','pay_fee','need_pay_fee','pay_date','number','unit_price','subject_code','tex',
        'expire_date','discount','items_name','describe','bond_status','lodged_date','bond_sn','refund_date','transfer_date','created_at','updated_at','deleted_at'];
}
