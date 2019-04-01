<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'full_name', 'code', 'sort_order', 'is_direct_city'
    ];

    public static function findByCode($provinceCode)
    {
        return Province::where('code', $provinceCode)->first();
    }
}
