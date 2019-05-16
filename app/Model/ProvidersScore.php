<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProvidersScore extends Model
{
    protected $table    = 'providers_score';
    protected $fillable = ['id','order_id','service_id','quality_score','community_score','money_score','score_detail','created_at','updated_at','deleted_at'];
}
