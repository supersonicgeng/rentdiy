<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LandlordOrder extends Model
{
    protected $table    = 'landlord_order';
    protected $fillable = ['id','rent_application_id','rent_contract_id','inspect_id','issue_id','user_id','tenement_id','order_sn','order_type','rent_house_id','District','TA','Region','start_time','end_time','requirement','budget','order_status','total_tender','created_at','updated_at','deleted_at'];
}
