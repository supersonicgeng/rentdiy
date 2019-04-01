<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $table    = 'operator';
    protected $fillable = ['id','user_id','operator_way','operator_account','operator_name','password','login_token','role','start_date','end_date','email','phone','is_use','created_at','updated_at','deleted_at'];
}
