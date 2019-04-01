<?php
namespace App\Lib\WeChat;

use App\Lib\Util\CurlRequest;
use Storage;
use App\Repositories\WeixinMpKeyRepository;

class WechatOAuthHelper
{
    private $authUrl = 'https://open.weixin.qq.com/';

    public function getOAuth()
    {
        $url = $authUrl.'/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
    }
}
