<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LandlordOrder extends Model
{
    protected $table    = 'landlord_order';
    protected $fillable = ['id','rent_application_id','rent_contract_id','landlord_id','tenement_id','order_sn','order_type','room_id','District','TA','Region','start_time','end_time','requirement','budget','order_status','created_at','updated_at','deleted_at'];
}
