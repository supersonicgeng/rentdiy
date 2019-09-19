<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function admin()
    {
        return $this->belongsTo(User::Class);
    }
}
