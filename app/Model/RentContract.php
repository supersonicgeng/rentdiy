<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentContract extends Model
{
    protected $table    = 'rent_contract';
    protected $fillable = ['id','contract_id','user_id','house_id','landlord_id','landlord_full_name','landlord_e_mail','house_address','landlord_mobile_phone','landlord_telephone','increment_date',
        'landlord_hm','landlord_wk','landlord_other_address','landlord_additional_address','landlord_wish','contract_type','contract_status','rent_start_date','rent_end_date','balance','created_at','updated_at','deleted_at'];

    public function getTenement()
    {
        return $this->hasOne('App\Model\ContractTenement','contract_id');
    }

    public function getEntireDetail()
    {
        return $this->hasOne('App\Model\EntireContract','contract_id');
    }

    public function getSeparateDetail()
    {
        return $this->hasOne('App\Model\SeparateContract','contract_id');
    }


    public function getBusinessDetail()
    {
        return $this->hasOne('App\Model\Businesstract','contract_id');
    }
}
