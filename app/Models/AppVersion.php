<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $guarded = [];
    protected $table = 'apps';

    /***
     * 渠道号
     */
    public function channel()
    {
        return $this->belongsTo(Channel::Class,'channel_id','id');
    }
}
