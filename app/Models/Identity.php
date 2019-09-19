<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    // 用户身份表
    protected $table = 'customer_identity';

    protected $fillable = ['id', 'identity'];
}
