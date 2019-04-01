<?php

namespace App\Http\Controllers\Wap;

use App\Jobs\SendPhone;
use App\Mail\Test;
use App\Model\Driver;
use App\Model\PinYin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IndexController extends CommonController
{
    public function index(Request $request){
        dump($request -> get('user'));
        return $this->display();
    }

    public function test(Request $request)
    {
        $app = app('wechat');;
       // $config = $app->js->buildConfig(['onMenuShareTimeline','onMenuShareAppMessage'], true);
        $config = $app->js->config(['onMenuShareTimeline','onMenuShareAppMessage'], true,$beta = false, false);
        //dump($config);exit;
        return view('wap.test',['config'=>$config]);
    }
}
