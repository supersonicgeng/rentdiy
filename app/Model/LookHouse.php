<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LookHouse extends Model
{
    protected $table    = 'look_house';
    protected $fillable = ['id','rent_application_id','recommendation_score','look_note','upload_url','check_name','created_at','updated_at','deleted_at'];
}
