<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BankCheck extends Model
{
    protected $table    = 'bank_check';
    protected $fillable = ['id','user_id','check_id','bank_check_date','bank_sn','amount','match_code','match_arrears_id','is_checked','bank_check_detail','match_tenement_id','match_tenement_name','created_at','updated_at','deleted_at'];
}
