<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentApplication extends Model
{
    protected $table    = 'rent_application';
    protected $fillable = ['id','rent_house_id','tenement_id','adult','children','tenement_people','income','income_cycle','rent_time','rent_time_cycle','start_time','end_rent_time','application_status','created_at','updated_at','deleted_at'];
}
