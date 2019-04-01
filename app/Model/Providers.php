<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Providers extends Model
{
    protected $table    = 'service_information';
    protected $fillable = ['id','user_id','service_name','first_name','middle_name','last_name','headimg','jobs','address','tax_no','phone','tel','email','license_no',
        'bank_account','maill_address','mail_code','about_us','created_at','updated_at','deleted_at'];
}
