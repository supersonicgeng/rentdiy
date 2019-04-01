<?php
/**
 * Created by PhpStorm.
 * User: huangkaiwang
 * Date: 2018/4/13
 * Time: 15:57
 */

namespace App\Services;

use App\Model\Good;
use App\Model\Order;
use App\Model\Passport;
use Carbon\Carbon;

/**
 * 数据统计服务层
 * Class StaticService
 * @package App\Services
 */
class StaticService extends CommonService
{
    /**
     * 首页统计数据
     * @author  hkw <hkw925@qq.com>
     */
    public function indexData()
    {
        $now_zero = Carbon::parse('today');
        $passport = new Passport();
        $index_data = [];
        $nickname = [];
        $total_score = [];
        $total_data = [
            'user_count'=>$passport->count(),
            'best_score'=>$passport->max('best_score')
        ];
        $score_list = $passport->orderByDesc('total_score')->limit(10)->get()->toArray();
        foreach ($score_list as $v){
            $nickname[]    = $v['nickname'];
            $total_score[] = $v['total_score'];
        }
        //dd($total_score);
        return ['total_data'=>$total_data,'nickname'=>$nickname,'total_score'=>$total_score];
    }
}