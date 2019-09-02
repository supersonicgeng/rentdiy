<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UnPlatInspectRoom extends Model
{
    protected $table    = 'unplat_inspect_room';
    protected $fillable = ['id','inspect_id','room_name','items','accept','photo1','photo2','photo3','photo4','inspect_note', 'video_url','created_at','updated_at','deleted_at'];
}
