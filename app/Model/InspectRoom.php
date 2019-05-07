<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InspectRoom extends Model
{
    protected $table    = 'inspect_room';
    protected $fillable = ['id','inspect_id','room_name','items','accept','photo1','photo2','photo3','photo4','note', 'created_at','updated_at','deleted_at'];
}
