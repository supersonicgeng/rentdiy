<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tenement extends Model
{
    protected $table    = 'tenement_information';
    protected $fillable = ['id','user_id','tenement_id','first_name','middle_name','last_name','mobile','phone','email','birthday','mail_address','service_address','mail_code','zip_code','bank_no','headimg',
        'contact_phone','hm','wk','contact_address','company','job_title','instruction','subject_code','created_at','updated_at','deleted_at'];
    protected $casts = [
        'phone'     => 'integer',
        'mobile'    => 'integer',
        'hm'        => 'integer',
        'wk'        => 'integer',
    ];
}
