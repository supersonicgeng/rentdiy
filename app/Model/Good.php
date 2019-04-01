<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'category_id',
        'title',
        'price',
        'reward1',
        'reward2',
        'store',
        'detail',
        'type',
        'status',
        'sales'
    ];

    public static $TYPE = [
        0 => '无需关联',
        1 => '代理商',
        2 => '电商积分',
        3 => '乐享还呗'
    ];
    public static $STATUS_TEXT = [
        '下架',
        '上架'
    ];
    public static $STATUS_ON = 1;
    public static $STATUS_OFF = 0;
    public static $VIP_TYPE = 1;

    public function category()
    {
        return $this->belongsTo('App\Model\GoodCategory', 'category_id');
    }

    public function album()
    {
        return $this->hasMany('App\Model\GoodAlbum', 'goods_id');
    }

    public function albumFirst()
    {
        $data = $this->album()->first();
        if ($data) {
            return imgShow($data->pic);
        } else {
            return '/index/img/ad1.jpg';
        }
    }

    public function descWithoutHtml()
    {
        return strip_tags($this->detail);
    }

    public function albumGet()
    {
        return $this->album()->get();
    }

    public function gift()
    {
        return $this->hasMany('App\Model\GoodGift', 'goods_id');
    }

    public function giftGet()
    {
        return $this->hasMany('App\Model\GoodGift', 'goods_id')->get();
    }
}
