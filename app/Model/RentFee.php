<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentFee extends Model
{
    protected $table    = 'rent_fee';
    protected $fillable = ['id','contract_id','contract_sn','user_id','rent_house_id','tenement_id','tenement_name','tenement_email','effect_date','rent_fee',
        'pay_rent_fee','arrears','fee_status','created_at','updated_at','deleted_at'];
}
