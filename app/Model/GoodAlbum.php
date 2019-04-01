<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodAlbum extends Model
{
    protected $table = 'goods_album';
    public $timestamps = false;
    protected $fillable = [
        'goods_id',
        'pic'
    ];
}
