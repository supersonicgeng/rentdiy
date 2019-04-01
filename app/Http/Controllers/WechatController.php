<?php

namespace App\Http\Controllers;

use App\Model\Passport;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        //Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function ($message) {
            if ($message->MsgType == 'event') {
                switch ($message->Event) {
                    case 'subscribe':
                        $passport = service('Passport')->subscribe($message->FromUserName);
                       /* $qrcodeId = explode('_', $message->EventKey)[1];
                        service('Qrcode')->qrcodeManage($qrcodeId, $passport);*/
                        return "欢迎关注啊~~~";
                        break;
                    case 'unsubscribe':
                        //取消关注
                        service('Passport')->unsubscribe($message->FromUserName);
                        break;
                    case 'SCAN':
                       /* $passport = Passport::where(['openid' => $message->FromUserName])->first();
                        $qrcodeId = $message->EventKey;
                        service('Qrcode')->qrcodeManage($qrcodeId, $passport);*/
                        break;
                    default:
                        break;
                }
            } else {
                //接收消息
                //return "母鸡啊";
            }
        });
        //Log::info('return response.');
        return $wechat->server->serve();
    }

    public function demo(Application $wechat)
    {
        $message = new Text(['content' => 'Hello world!']);
        $result  = $wechat->staff->message($message)->to('ogD0sszl2mSS1zQRpl_AfsvAzYUs')->send();
    }
}
