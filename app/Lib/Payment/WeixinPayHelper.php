<?php
namespace App\Lib\Payment;

use App\Lib\Util\CurlRequest;
use App\Lib\Util\StringUtil;

class WeixinPayHelper
{
    private $appid = null;
    private $mchId = null;
    private $keyConfig = null;
    private $clientType = null; //客户端类型,0=微信公众号,1=IOS,2=安卓
    private $deviceInfo = 'WEB';
    private $body = '优优照护';
    private $feeType = 'CNY';
    private $notifyUrl = '';
    private $openId = null;
    private $productId = null;

    public function __construct($clientType=0)
    {
        $this->clientType = $clientType;
        $appid = $this->getAppId();
        $mchId = $this->getMchId();
        $keyConfig = $this->getSignatureKey();
        $this->init($appid, $mchId, $keyConfig);
    }

    public static function guessClientTypeByAppId($appid)
    {
        if (config('weixinpay.app_id') == $appid) {
            return 0;
        } else if (config('weixinpay.APP_app_id')['1'] == $appid) {
            return 1;
        } else if (config('weixinpay.APP_app_id')['2'] == $appid) {
            return 2;
        }
        return -1;
    }

    public function init($appid, $mchId, $keyConfig)
    {
        $this->appid = $appid;
        $this->mchId = $mchId;
        $this->keyConfig = $keyConfig;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    //设置openId,只有当tradeType=JSAPI时需要设置
    public function setOpenId($openId)
    {
        $this->openId = $openId;
    }

    //设置productId,只有当tradeType=NATIVE时需要设置
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getPrepayInfo($orderNo, $serviceName, $productName, $totalFee, $clientIp, $tradeType='APP')
    {
        $nonceStr = StringUtil::guid();
        $timeStart = date('YmdHis');

        $inputData = $this->buildPrepayXml($orderNo, $serviceName, $productName, $totalFee, $clientIp, $nonceStr, $timeStart, $tradeType);

        $curl = new CurlRequest();
        $xResult = $curl->httpPostXML('https://api.mch.weixin.qq.com/pay/unifiedorder', $inputData);

        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xResult, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    private function getAppId()
    {
        if ($this->clientType == 0) {
            return config('weixinpay.app_id');
        } else if ($this->clientType == 1) {
            return config('weixinpay.APP_app_id')['1'];
        } else if ($this->clientType == 2) {
            return config('weixinpay.APP_app_id')['2'];
        }
        return null;
    }

    private function getMchId()
    {
        if ($this->clientType == 0) {
            return config('weixinpay.mch_id');
        } else if ($this->clientType == 1) {
            return config('weixinpay.APP_mch_id')['1'];
        } else if ($this->clientType == 2) {
            return config('weixinpay.APP_mch_id')['2'];
        }
        return null;
    }

    private function getPrepaySignature($orderNo, $serviceName, $productName, $totalFee, $clientIp, $nonceStr, $timeStart, $tradeType)
    {
        $bodyInfo = $this->body.'-'.$serviceName;

        $preInfo = 'appid='.$this->appid
            .'&body='.$bodyInfo
            .'&detail='.$productName
            .'&device_info='.$this->deviceInfo
            .'&fee_type='.$this->feeType
            .'&mch_id='.$this->mchId
            .'&nonce_str='.$nonceStr
            .'&notify_url='.$this->notifyUrl;

        if ($tradeType == 'JSAPI') {
            $preInfo .= '&openid='.$this->openId;
        }

        $preInfo .= '&out_trade_no='.$orderNo;

        if ($tradeType == 'NATIVE') {
            $preInfo .= '&product_id='.$this->productId;
        }

        $timeExpire = $timeStart + 300;

        $preInfo = $preInfo.'&spbill_create_ip='.$clientIp
            .'&time_expire='.$timeExpire
            .'&time_start='.$timeStart
            .'&total_fee='.$totalFee
            .'&trade_type='.$tradeType
            .'&key='.$this->getSignatureKey();

        return strtoupper(md5($preInfo));
    }

    private function getSignatureKey()
    {
        if ($this->clientType == 0) {
            return config('weixinpay.app_key');
        } else if ($this->clientType == 1) {
            return config('weixinpay.APP_app_key')['1'];
        } else if ($this->clientType == 2) {
            return config('weixinpay.APP_app_key')['2'];
        }
        return null;
    }

    private function buildPrepayXml($orderNo, $serviceName, $productName, $totalFee, $clientIp, $nonceStr, $timeStart, $tradeType)
    {
        $bodyInfo = $this->body.'-'.$serviceName;

        $timeExpire = $timeStart + 300;

        $template = "<xml>\n<appid>".$this->appid."</appid>\n"
            ."<mch_id>".$this->mchId."</mch_id>\n"
            ."<device_info>".$this->deviceInfo."</device_info>\n"
            ."<nonce_str>$nonceStr</nonce_str>\n"
            ."<body>$bodyInfo</body>\n"
            ."<detail>$productName</detail>\n"
            ."<out_trade_no>$orderNo</out_trade_no>\n"
            ."<fee_type>".$this->feeType."</fee_type>\n"
            ."<total_fee>$totalFee</total_fee>\n"
            ."<spbill_create_ip>$clientIp</spbill_create_ip>\n"
            ."<time_start>$timeStart</time_start>\n"
            ."<time_expire>$timeExpire</time_expire>\n"
            ."<notify_url>".$this->notifyUrl."</notify_url>\n"
            ."<trade_type>$tradeType</trade_type>\n";

        if ($tradeType == 'NATIVE') {
            $template .= "<product_id>".$this->productId."</product_id>\n";
        } else if ($tradeType == 'JSAPI') {
            $template .= "<openid>".$this->openId."</openid>\n";
        }

        $template = $template."<sign>".$this->getPrepaySignature($orderNo, $serviceName, $productName, $totalFee, $clientIp, $nonceStr, $timeStart, $tradeType)."</sign>\n</xml>";

        return $template;
    }

    public function getNoticeCallbackSignature($responseObj)
    {
        $keyval = '';
        if (isset($responseObj->appid)) {
            $keyval .= 'appid='.$responseObj->appid.'&';
        }
        if (isset($responseObj->attach)) {
            $keyval .= 'attach='.$responseObj->attach.'&';
        }
        if (isset($responseObj->bank_type)) {
            $keyval .= 'bank_type='.$responseObj->bank_type.'&';
        }
        if (isset($responseObj->cash_fee)) {
            $keyval .= 'cash_fee='.$responseObj->cash_fee.'&';
        }
        if (isset($responseObj->cash_fee_type)) {
            $keyval .= 'cash_fee_type='.$responseObj->cash_fee_type.'&';
        }
        if (isset($responseObj->coupon_count)) {
            $keyval .= 'coupon_count='.$responseObj->coupon_count.'&';
        }
        if (isset($responseObj->device_info)) {
            $keyval .= 'device_info='.$responseObj->device_info.'&';
        }
        if (isset($responseObj->err_code)) {
            $keyval .= 'err_code='.$responseObj->err_code.'&';
        }
        if (isset($responseObj->err_code_des)) {
            $keyval .= 'err_code_des='.$responseObj->err_code_des.'&';
        }
        if (isset($responseObj->fee_type)) {
            $keyval .= 'fee_type='.$responseObj->fee_type.'&';
        }
        if (isset($responseObj->is_subscribe)) {
            $keyval .= 'is_subscribe='.$responseObj->is_subscribe.'&';
        }
        if (isset($responseObj->mch_id)) {
            $keyval .= 'mch_id='.$responseObj->mch_id.'&';
        }
        if (isset($responseObj->nonce_str)) {
            $keyval .= 'nonce_str='.$responseObj->nonce_str.'&';
        }
        if (isset($responseObj->openid)) {
            $keyval .= 'openid='.$responseObj->openid.'&';
        }
        if (isset($responseObj->out_trade_no)) {
            $keyval .= 'out_trade_no='.$responseObj->out_trade_no.'&';
        }
        if (isset($responseObj->result_code)) {
            $keyval .= 'result_code='.$responseObj->result_code.'&';
        }
        if (isset($responseObj->return_code)) {
            $keyval .= 'return_code='.$responseObj->return_code.'&';
        }
        if (isset($responseObj->time_end)) {
            $keyval .= 'time_end='.$responseObj->time_end.'&';
        }
        if (isset($responseObj->total_fee)) {
            $keyval .= 'total_fee='.$responseObj->total_fee.'&';
        }
        if (isset($responseObj->trade_type)) {
            $keyval .= 'trade_type='.$responseObj->trade_type.'&';
        }
        if (isset($responseObj->transaction_id)) {
            $keyval .= 'transaction_id='.$responseObj->transaction_id.'&';
        }

        $keyval .= 'key='.$this->getSignatureKey();

        return strtoupper(md5($keyval));
    }

    public function responseToNoticeWeixinpaySuccess()
    {
        echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
    }
}
