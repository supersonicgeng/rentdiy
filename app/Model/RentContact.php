<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RentContact extends Model
{
    protected $table    = 'rent_contact';
    protected $fillable = ['id','rent_house_id','contact_name','contact_role','e_mail','phone','created_at','updated_at','deleted_at'];
}
