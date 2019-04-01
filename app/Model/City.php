<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'full_name', 'code', 'sort_order', 'region_code', 'zip_code', 'province_id', 'can_service_flag'
    ];

    public static function findByCode($cityCode)
    {
        return City::where('code', $cityCode)->first();
    }

    public function province()
    {
        return $this->belongsTo('App\Province', 'province_id');
    }
}
