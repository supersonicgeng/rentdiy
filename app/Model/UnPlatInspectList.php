<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UnPlatInspectList extends Model
{
    protected $table    = 'unplat_inspect_list';
    protected $fillable = ['id','user_id','property_name','property_address','bedroom_num','bathroom_num','landlord_name','landlord_post_address','landlord_email','landlord_phone','property_type','start_time',
        'end_time','inspect_status','inspect_category','chattel_note','inspect_note','created_at','updated_at','deleted_at'];
}
