<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BusinessContract extends Model
{
    protected $table    = 'rent_business_contract';
    protected $fillable = ['id','contract_id','premises','car_parks','lease_term','term_method','commencement_date','final_expiry_date','renewal_time',
        'annual_rent','premises_pro','premises_gst','car_parks_pro','car_gst','total','total_gst','month_rent','rent_payment_date','day_each_month',
        'market_rent_assessment_date','cpi_date','outgoing','default_interest_rate','commercial_use','business_use','insurance','no_access_period',
        'further_term','tax_apy_local','tax_apy_local_detail','hydroelectric','hydroelectric_detail','garbage_collection','garbage_collection_detail',
        'fire_service','fire_service_detail','insurance_excess','insurance_excess_detail','air_conditioning','air_conditioning_detail','provide_toilets',
        'provide_toilets_detail','maintenance_cost_for_garden','maintenance_cost_for_garden_detail','maintenance_cost_for_parks','maintenance_cost_for_parks_detail',
        'management_cost','management_cost_detail','incurred_cost','incurred_cost_detail','fixtures_fittings','fixtures_fittings_upload_url','premises_condition',
        'premises_condition_upload_url','notes','notes_upload_url','landlord_signature','tenement_signature','created_at','updated_at','deleted_at'];
}
