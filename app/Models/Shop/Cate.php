<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Cate extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    /***
     * 查子分类
     */
    public function children()
    {
        return $this->hasMany(self::Class, 'parent_id', 'id');
    }

    /***
     * 分类下商品
     */
    public function goods()
    {
        return $this->hasMany(Good::Class, 'cate_id', 'id');
    }

    /***
     * 父级分类
     */
    public function f_cate()
    {
        return $this->belongsTo(self::Class, 'parent_id', 'id');

    }

    static function get_categories()
    {
        //设置缓存,存入shop_categories

        $categories = Cache::rememberForever('jhm_categories', function () {
            return self::with([
                'children' => function ($query) {
                    $query->orderBy('sort');
                }
            ])->where('parent_id', 0)->orderBy('sort', 'desc')->get(); //一级分类
        });

        return $categories;
    }
}
