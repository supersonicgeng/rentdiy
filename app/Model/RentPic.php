<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentPic extends Model
{
    protected $table    = 'rent_pic';
    protected $fillable = ['id','rent_house_id','house_pic','created_at','updated_at','deleted_at'];
}
