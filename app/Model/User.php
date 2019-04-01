<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table    = 'user';
    protected $fillable = ['id','username','head_img','sexy','nickname','phone','facebook_id','twitter_id','wechat_openid','login_token','login_expire_time',
        'user_role','house_number','jobs','email','password','created_at','updated_at','deleted_at'];
}
