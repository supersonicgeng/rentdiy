<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
    protected $table    = 'key';
    protected $fillable = ['id','user_id','house_id','key_name','key_status','borrow_name','tel','e_mail','borrow_start_date','borrow_end_date','return_date','operator_name','key_no',
        'note','created_at','updated_at','deleted_at'];
}
