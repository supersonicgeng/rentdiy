<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProvidersCompanyPic extends Model
{
    protected $table    = 'service_company_pic';
    protected $fillable = ['id','service_id','company_pic','created_at','updated_at','deleted_at'];
}
