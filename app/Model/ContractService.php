<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContractService extends Model
{
    protected $table    = 'contract_service';
    protected $fillable = ['id','contract_id','service_name','service_price','created_at','updated_at','deleted_at'];
}
