<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LandlordOrderScore extends Model
{
    protected $table    = 'landlord_order_score';
    protected $fillable = ['id','order_id','landlord_user_id','providers_id','community_score','pay_score','score_note','created_at','updated_at','deleted_at'];
}
