<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User_score extends Model
{
    protected $table      ='user_score' ;
    protected $primaryKey ='id';
    protected $fillable   = ['current_score','best_score','add_up_score','recommend_score','total_score'];

    public function findByPassportId($passport_id)
    {
        return User_score::where('passport_id', $passport_id)->get();
    }
}
