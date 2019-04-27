<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OtherRentApplication extends Model
{
    protected $table    = 'other_rent_application';
    protected $fillable = ['id','tenement_id','apply_house_address','apply_start_time','apply_end_time','tenement_address','tenement_name','birthday','phone','mobile','email','welfare_no',
        'have_pets','pets','current_address','current_rent_fee','rent_times','rent_way','live_method','other_method','leave_reason','current_landlord_name','landlord_phone','landlord_email',
        'property_manager_name','manager_phone','manager_email','inform_landlord','driving_license','version_num','passport','vehicle','alternative','model','work_situation','company_name',
        'job_title','employer_name','company_address','company_phone','company_email','inform_company','income','contact_name','contact_address','contact_phone','contact_mobile',
        'contact_email','contact_relation','recommend_name1','recommend_email1','recommend_tel1','recommend_relation1','recommend_name2','recommend_email2','recommend_tel2',
        'recommend_relation2','sign','created_at','updated_at','deleted_at'];
}

