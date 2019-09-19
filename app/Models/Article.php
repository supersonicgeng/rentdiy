<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = [];

    /***
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 创建管理员
     */
    public function admin()
    {
        return $this->belongsTo(User::Class);
    }

    /***
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 栏目
     */
    public function cate()
    {
        return $this->belongsTo(Category::Class,'category','id');
    }
}
