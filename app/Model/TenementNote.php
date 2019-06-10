<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TenementNote extends Model
{
    protected $table    = 'tenement_note';
    protected $fillable = ['id','tenement_id','user_id','tenement_note','created_at','updated_at','deleted_at'];
}
