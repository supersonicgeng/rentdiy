<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Bond extends Model
{
    protected $table    = 'bond';
    protected $fillable = ['id','contract_id','contract_sn','tenement_name','bond_status','property_name','tenement_phone','tenement_email','total_bond','created_at','updated_at','deleted_at'];
}
