<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InspectCheck extends Model
{
    protected $table    = 'inspect_check';
    protected $fillable = ['id','inspect_id','inspector_note','tenement_note', 'other_note','upload_url','inspector_sign','tenement_sign','created_at','updated_at','deleted_at'];
}
