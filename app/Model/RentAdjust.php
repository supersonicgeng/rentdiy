<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentAdjust extends Model
{
    protected $table    = 'rent_adjust';
    protected $fillable = ['id','contract_id','rent_fee_method','rent_price','effective_date','created_at','updated_at','deleted_at'];
}
