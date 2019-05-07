<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InspectChattel extends Model
{
    protected $table    = 'inspect_chattel';
    protected $fillable = ['id','inspect_id','chattel_name','chattel_num', 'created_at','updated_at','deleted_at'];
}
