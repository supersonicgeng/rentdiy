<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TenementScore extends Model
{
    protected $table    = 'tenement_score';
    protected $fillable = ['id','tenement_id','tenement_name','user_id','pay_score','hygiene_score','facility_score','detail','contract_id','rent_house_id','accept_again','birthday','created_at','updated_at','deleted_at'];
}
