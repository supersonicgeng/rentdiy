<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SerciceList extends Model
{
    protected $table    = 'service_list';
    protected $fillable = ['id','service_id','house_pic','created_at','updated_at','deleted_at'];
}
