<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
{
    protected $table    = 'landlord_information';
    protected $fillable = ['id','user_id','landlord_name','first_name','middle_name','last_name','headimg','address','assign_name','tax_no','phone','tel','email',
        'bank_account','maill_address','mail_code','notice','created_at','updated_at','deleted_at'];
}
