<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodGift extends Model
{
    protected $table = 'goods_gift';
    public $timestamps = false;
    protected $fillable = [
        'goods_id',
        'type',
        'qty'
    ];

    public function goods()
    {
        return $this->belongsTo('App\Model\Good','goods_id','id');
    }
}
