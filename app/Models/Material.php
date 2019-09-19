<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $guarded = [];
    protected $table = 'material';


    /**
     * 发布人
     */
    public function matuser()
    {
        return $this->belongsTo(Matuser::Class,'muid','mid');
    }
}
