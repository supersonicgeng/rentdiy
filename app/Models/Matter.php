<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matter extends Model
{
    protected $guarded = [];

    public static function limitNum($id)
    {
        $matter = self::find($id);

        $subject_num = Subject::where('matter_id', $id)->where('is_on', 1)->count();

        if ($matter->subject_num <= $subject_num) {
            return 0;
        }

        return 1;
    }
}
