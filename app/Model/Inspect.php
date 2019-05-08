<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Inspect extends Model
{
    protected $table    = 'inspect_list';
    protected $fillable = ['id','rent_house_id','contract_id','inspect_name','inspect_status','inspect_method','inspect_category','inspect_start_date','inspect_end_date','inspect_completed_date','inspect_note','chattel_note',
        'created_at','updated_at','deleted_at'];
}
