<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SendMessage extends Model
{
    protected $table    = 'send_message';
    protected $fillable = ['id','user_id','receive_user_id','contract_id','msg_send','email_send','paper_send','content','msg_type','send_status','created_at','updated_at','deleted_at'];
}
