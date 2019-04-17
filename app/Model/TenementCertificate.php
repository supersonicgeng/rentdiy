<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TenementCertificate extends Model
{
    protected $table    = 'tenement_certificate';
    protected $fillable = ['id','tenement_id','certificate_category','certificate_no','certificate_pic1','certificate_pic2','created_at','updated_at','deleted_at'];
}
