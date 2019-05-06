<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContractChattel extends Model
{
    protected $table    = 'contract_chattel';
    protected $fillable = ['id','contract_id','rent_house_id','chattel','chattel_num','note','created_at','updated_at','deleted_at'];
}
