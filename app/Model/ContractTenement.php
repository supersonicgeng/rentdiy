<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContractTenement extends Model
{
    protected $table    = 'contract_tenement';
    protected $fillable = ['id','contract_id','tenement_id','tenement_full_name','identification_no','identification_type','service_physical_address','tenement_e_mail','tenement_phone',
        'tenement_mobile','tenement_hm','tenement_wk','tenement_post_address','tenement_post_code','tenement_service_address','other_contact_address','additional_address',
        'guarantor_name','occupation','home_address','guarantor_phone','guarantor_e_mail','is_child','created_at','updated_at','deleted_at'];
}
