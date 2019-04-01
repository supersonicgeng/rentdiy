<?php

namespace App\Http\Controllers;

use App\Mail\Test;
use App\Model\PassportReward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use EasyWeChat;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{

    public function index(){
        /*dd(service('Passport')->doEvaluate([
            'passport_id'=>9,
            'plant_id'=>1,
            'route_item_id'=>1,
            'do_passport_id'=>8,
            'position'=>1,
            'tag1'=>3,
            'tag2'=>4,
            'tag3'=>5,
            'content'=>'buhaoadsasd',
            'star'=>3
        ]));*/
        //dd(getenv('APP_ENV'));
        //dd(env('APP_ENV') == 'local');
        //service('Plant')->quoteSurePush(Driver::find(1),Plant::find(3));
        //dd(service('AliSms')->verifyCode(17671231208,792837));
        //service('AliSms')->sendCode(17671231208);
        //dd(public_path('robots.txt'));
        //$list = service('Wish')->indexData();
        //dd($list);
        //$payment = EasyWeChat::payment();
        //service('Qrcode')->personCode(1);
        /*$r = service('Passport')->financeAdd([
            'passport_id'=>1,
            'type'=>1,
            'account'=>'943712683@qq.com',
            'username'=>'黄开旺',
            'money'=>100
        ]);
        dump($r);*/
        //echo PassportReward::createApplyNumber();
        //echo createOrderNo();
        //dump(service('Common')->checkHisums());
        //echo Carbon::today();
        dump(222);
    }
}
