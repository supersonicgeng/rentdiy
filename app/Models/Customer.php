<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    protected $table = 'customer';

    public function incomeInfo()
    {
        return $this->hasOne(CustomerInfo::Class, 'customer_id', 'id');
    }

    /**
     * 用户标签
     */
    public function identity()
    {
        return $this->belongsTo(Identity::Class, 'identity_id', 'id');
    }
}
