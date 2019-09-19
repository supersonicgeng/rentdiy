<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\Subject;

Class RedisService
{
    /***
     * 更新banner缓存
     */
    public static function updateCache($subject,$matter_id)
    {
        $res = Subject::where('matter_id', $matter_id)
            ->where('is_on', 1)
//            ->where('start_at', '<', Timeformat(time()))
//            ->where('stop_at', '>', Timeformat(time()))
            ->orderBy('sort', 'desc')
            ->get();

        Redis::set('jhm_'.$subject,json_encode($res,JSON_UNESCAPED_UNICODE));
    }


}
