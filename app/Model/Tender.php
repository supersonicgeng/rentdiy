<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $table    = 'tender';
    protected $fillable = ['id','service_id','order_id','quota_price','tender_note','tender_status','created_at','updated_at','deleted_at'];
}
