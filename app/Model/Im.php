<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Im extends Model
{
    protected $table    = 'im_info';
    protected $fillable = ['id','from','to','msg','created_at','updated_at','deleted_at'];
}
