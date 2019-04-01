<?php
namespace App\Lib\WeChat;

use App\Lib\Util\CurlRequest;
use Storage;
use App\Helpers\WeixinMpKeyHelper;

class WechatMenu
{
    protected $weChatKey;
    protected $appId;
    protected $appSecret;
    protected $curl;

    private function getCurrentWxMpKey()
    {
        return config('constants.MPKEY');
    }

    public function __construct()
    {
        $mpKey = $this->getCurrentWxMpKey();
        $weixinMpkeyHelper = new WeixinMpKeyHelper();
        $this->weChatKey = $weixinMpkeyHelper->findMpKey($mpKey);
        $this->appId = $this->weChatKey->mp_app_id;
        $this->appSecret = $this->weChatKey->mp_app_secret;
        $this->curl = new CurlRequest();
    }
    /*
     * return json if success : {"errcode":0,"errmsg":"ok"}
     * return json if error : {"errcode":40018,"errmsg":"invalid button name size"}
     */
    public function getMenuCreated($data)
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$accessToken";
        return $this->curl->httpPost($url,$data);
    }

    /*
    * return json if success: {"errcode":0,"errmsg":"ok"}
    */

    public function getMenuDeleted()
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$accessToken";
        return $this->curl->httpGet($url);
    }

    /*
     * return json if success, like:{"menu":{"button":[{"type":"click","name":"今日歌曲","key":"V1001_TODAY_MUSIC","sub_button":[]},
     *
     */
    public function getMenu()
    {
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=$accessToken";
        return $this->curl->httpGet($url);
    }

    private function getTokenTempFile()
    {
        if (Storage::disk('local')->exists('access_token.json')) {
            $fileContents = Storage::disk('local')->get('access_token.json');
            if (!empty($fileContents)) {
                return json_decode($fileContents);
            }
            return null;
        }
        return null;
    }

    private function freshNewToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
        $res = json_decode($this->curl->httpGet($url));
        $accessToken = $res->access_token;
        return $accessToken;
    }

    private function storeToken($accessToken)
    {
        $expireTime = time() + 7000;
        Storage::disk('local')->put('access_token.json', json_encode([
            'access_token' => $accessToken,
            'expire_time' => $expireTime
        ]));
    }

    private function getAccessToken()
    {
        $data = $this->getTokenTempFile();
        if (isset($data)) {
            if (intval($data->expire_time) < time()) {
                $newToken = $this->freshNewToken();
                $this->storeToken($newToken);
            } else {
                return $data->access_token;
            }
        } else {
            $newToken = $this->freshNewToken();
            $this->storeToken($newToken);
        }
        return $newToken;
    }
}
