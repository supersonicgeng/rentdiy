<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    /**
     * 与标签多对多
     */
    public function goods()
    {
        return $this->belongsToMany(Good::Class, 'good_tags', 'tag_id', 'good_id');
    }
}
