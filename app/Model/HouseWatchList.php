<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class HouseWatchList extends Model
{
    protected $table    = 'house_watch_list';
    protected $fillable = ['id','tenement_id','rent_house_id','created_at','updated_at','deleted_at'];
}
