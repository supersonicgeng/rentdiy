<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $table    = 'repair';
    protected $fillable = ['id','order_id','tender_id','items_id','items_tender_price','created_at','updated_at','deleted_at'];
}
