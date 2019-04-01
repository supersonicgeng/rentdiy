<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProvidersCompanyPromoPic extends Model
{
    protected $table    = 'service_company_promo_pic';
    protected $fillable = ['id','service_id','company_promo_pic','created_at','updated_at','deleted_at'];
}
