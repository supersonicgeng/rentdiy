<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    protected $table    = 'landlord_information';
    protected $fillable = ['id','user_id','landlord_sn','landlord_name','property_address','first_name','middle_name','last_name','headimg','assign_name','tax_no','phone','mobile','email',
        'bank_account','hm','wk','maill_address','mail_code','notice','created_at','updated_at','deleted_at'];
    protected $casts = [
        'phone'     => 'integer',
        'mobile'    => 'integer',
        'hm'        => 'integer',
        'wk'        => 'integer',
    ];
}
