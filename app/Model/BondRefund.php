<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BondRefund extends Model
{
    protected $table    = 'bond_refund';
    protected $fillable = ['id','bond_id','tenement_id','tenement_full_name','tenement_account','landlord_id','landlord_full_name','landlord_account','created_at','updated_at','deleted_at'];
}
