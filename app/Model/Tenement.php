<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tenement extends Model
{
    protected $table    = 'tenement_information';
    protected $fillable = ['id','user_id','first_name','middle_name','last_name','tel','phone','email','birthday','address','mail_code','bank_no','headimg',
        'first_credentials_name','second_credentials_name','contact_phone','contact_address','company','job_title','instruction','first_credentials_code','first_credentials_pic',
        'second_credentials_code','second_credentials_pic','created_at','updated_at','deleted_at'];
}
