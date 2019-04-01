<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Verify extends Model
{
    protected $table    = 'verify_code';
    protected $fillable = ['id','account','verify_type','code','verify_status','expire_time'];
}
