<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $guarded = [];

    /***
     * 关联管理员
     */
    public function admin()
    {
        return $this->belongsTo('App\User');
    }
}
