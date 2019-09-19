<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];
    protected $table = 'app_channel';

    /***
     * 管理员
     */
    public function admin()
    {
        return $this->belongsTo('App\User');
    }
}
