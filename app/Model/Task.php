<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table    = 'task';
    protected $fillable = ['id','user_id','task_type','task_start_time','task_end_time','task_status','task_color','task_title','task_content','rent_house_id','contract_id','inspect_id','repair_id','task_role','created_at','updated_at','deleted_at'];
}
