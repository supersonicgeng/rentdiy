<?php

namespace App\Models\Shop;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $guarded = [];

    /***
     * 一级分类
     */
    public function f_cate()
    {
        return $this->belongsTo(Cate::Class, 'one_cate_id', 'id');
    }

    /***
     * 二级分类
     */
    public function s_cate()
    {
        return $this->belongsTo(Cate::Class, 'cate_id', 'id');

    }

    /**
     * 与标签多对多
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::Class, 'good_tags', 'good_id', 'tag_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::Class);
    }
}
