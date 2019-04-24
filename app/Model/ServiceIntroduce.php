<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ServiceIntroduce extends Model
{
    protected $table    = 'service_introduce';
    protected $fillable = ['id','service_id','service_name','price','is_gts','details','created_at','updated_at','deleted_at'];
}
