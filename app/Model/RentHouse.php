<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentHouse extends Model
{
    protected $table    = 'rent_house';
    protected $fillable = ['id','user_id','group_id','rent_category','property_name','room_name','details','room_description','shower_room','property_type','bathroom_type','bathroom_no','bedroom_no','bed_no','require_renter','short_words',
        'actual_area','building_area','parking_no','garage_no','insurance_company','insurance_start_time','insurance_end_time','address','District','TA','Region','lat','lon','bus_station','school','supermarket',
        'hospital','business_equip','rent_period','rent_least_fee','rent_fee_detail','rent_fee','rent_fee_pre_week','available_time','least_rent_time','least_rent_method','pre_rent','pre_rent_fee','margin_rent','margin_rent_fee','total_need_fee',
        'can_party','can_pet','can_smoke','other_rule','rent_method','rent_status','last_check_date','is_put','available_date','created_at','updated_at','deleted_at'];


    public function getPic()
    {
        return $this->hasMany('App\Model\RentPic','rent_house_id');
    }
}
