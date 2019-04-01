<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OperatorRoom extends Model
{
    protected $table    = 'operator_room';
    protected $fillable = ['id','operator_id','house_id','created_at','updated_at','deleted_at'];
}
