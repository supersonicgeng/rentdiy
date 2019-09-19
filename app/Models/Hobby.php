<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    // 用户爱好表
    protected $table = 'customer_hobby';

    protected $fillable = ['id', 'hobby'];
}
