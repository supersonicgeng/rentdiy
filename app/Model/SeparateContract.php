<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SeparateContract extends Model
{
    protected $table    = 'rent_separate_contract';
    protected $fillable = ['id','contract_id','agent_name','agent_address','agent_e_mail','agent_phone','agent_mobile','agent_hm','agent_wk','agent_other_address',
        'agent_additional_address','tenancy_address','rent_per_week','is_house_rule','house_rule_url','is_fire','fire_url','is_body','body_url','pay_method','bond_amount','to_be_paid','rent_to_be_paid_at','bank_account','account_name',
        'bank','branch','agree_date','intended','is_joint_tenancy','is_joint_tenancy_detail','is_not_share','is_share_people','allow_service', 'is_ceiling_insulation','ceiling_insulation_detail','ceiling_insulation_detail',
        'is_insulation_underfloor_insulation','insulation_underfloor_insulation_detail','location_ceiling_insulation','location_ceiling_insulation_detail','ceiling_insulation_type',
        'ceiling_insulation_type_detail','R_value','minimum_thickness','ceiling_insulation_age','ceiling_insulation_condition','ceiling_insulation_condition_reason',
        'location_underfloor_insulation','location_underfloor_insulation_detail','underfloor_insulation_type','underfloor_insulation_type_detail',
        'underfloor_R_value','underfloor_minimum_thickness','condition','condition_detail','wall_insulation','wall_insulation_detail','supplementary_information',
        'install_insulation','install_insulation_detail','underfloor_insulation','underfloor_insulation_detail','last_upgraded','professionally_assessed','plan',
        'landlord_state','landlord_signature','sign_date','tenement_signature','rent_end_date','rent_fee','created_at','updated_at','deleted_at'];
}
