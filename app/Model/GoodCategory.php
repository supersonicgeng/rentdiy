<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodCategory extends Model
{
    use SoftDeletes;
    protected $table = 'goods_category';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'pid',
        'pic',
        'sort'
    ];
}
