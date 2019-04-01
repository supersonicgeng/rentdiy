<?php
/**
 * Created by PhpStorm.
 * User: huangkaiwang
 * Date: 2018/4/11
 * Time: 14:35
 */

namespace App\Services;

/**
 * 小程序二维码
 * Class QrcodeService
 * @package App\Services
 */
class QrcodeService extends CommonService
{
    /**
     * 个人二维码
     * @author  hkw <hkw925@qq.com>
     * @param $user_id
     * @return mixed
     */
    public function personCode($user_id)
    {
        $access_token = app('wechat')->access_token->getToken();
        $url          = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
        $ch           = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        //post变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['scene' => 'uid_' . $user_id, 'page' => 'pages/index/index']));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * 详情二维码
     * @author  hkw <hkw925@qq.com>
     * @param $user_id
     * @return mixed
     */
    public function detailCode($user_id, $goods_id)
    {
        $access_token = app('wechat')->access_token->getToken();
        $url          = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
        $ch           = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        //post变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['scene' => 'd_' . $user_id . '_' . $goods_id, 'page' => 'pages/goodsDetail/goodsDetail','path'=>'pages/goodsDetail/goodsDetail']));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}