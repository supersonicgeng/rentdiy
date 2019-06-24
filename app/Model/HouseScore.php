<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HouseScore extends Model
{
    protected $table    = 'house_score';
    protected $fillable = ['id','user_id','pay_score','hygiene_score','facility_score','detail','contract_id','rent_house_id','created_at','updated_at','deleted_at'];
}
