<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InspectCheck extends Model
{
    protected $table    = 'inspect_check';
    protected $fillable = ['id','inspect_id','inspector_note','tenement_note', 'other_note','upload_url','inspector_sign','tenement_sign','repair_note','select1','select2','select3','select4','select5','select6','select7','created_at','updated_at','deleted_at'];
}
