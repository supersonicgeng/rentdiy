<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Special extends Model
{
    protected $fillable = ['title','goods_id','is_and','cates_id','price_min','price_max','is_fy'];

    protected $table = 'special';
}
