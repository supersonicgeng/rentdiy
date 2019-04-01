<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Wechat extends Model
{
    public static $GET_OPENID_BY_CODE_URL = 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code';
}
