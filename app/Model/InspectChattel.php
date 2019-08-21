<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InspectChattel extends Model
{
    protected $table    = 'inspect_chattel';
    protected $fillable = ['id','inspect_id','chattel_name','chattel_num','accept','photo1','photo2','photo3','photo4','inspect_note', 'created_at','updated_at','deleted_at'];
}
