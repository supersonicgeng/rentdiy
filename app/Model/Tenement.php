<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tenement extends Model
{
    protected $table    = 'tenement_information';
    protected $fillable = ['id','user_id','tenement_id','first_name','middle_name','last_name','mobile','phone','email','birthday','address','mail_code','bank_no','headimg',
        'contact_phone','contact_address','company','job_title','instruction','created_at','updated_at','deleted_at'];
}
