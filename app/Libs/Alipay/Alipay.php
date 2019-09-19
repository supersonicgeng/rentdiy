<?php

namespace App\Libs\Alipay;

// require_once "AopSdk.php";
use App\Libs\Alipay\AopSdk;
use Route;

Class Alipay
{
    protected $aop;

    public function __construct()
    {

        $this->aop = new \AopClient();
        $this->aop->gatewayUrl = config('alipay.gatewayUrl');
        $this->aop->appId = config('alipay.app_id');
        $this->aop->rsaPrivateKey = config('alipay.merchant_private_key');
        $this->aop->format = "json";
        $this->aop->charset = "UTF-8";
        $this->aop->signType = "RSA2";
        $this->aop->apiVersion = "1.0";
        $this->aop->alipayrsaPublicKey = config('alipay.alipay_public_key');

    }


    /***
     * 网页支付
     */
    public function pagePay($out_trade_no, $total_amount, $subject)
    {
        $request = new \AlipayTradePagePayRequest ();
        $request->setReturnUrl(Route('alipay.return_url'));
        $request->setNotifyUrl(Route('alipay.notify_url'));

        $bizcontent = [
            'out_trade_no' => $out_trade_no, //商户订单号，64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
            'total_amount' => $total_amount,//订单金额
            'timeout_express' => '30m', //订单逾期支付时间 此处30分钟
            'subject' => $subject, //订单标题
        ];
        $request->setBizContent(json_encode($bizcontent, JSON_UNESCAPED_UNICODE));


        return $this->aop->pageExecute($request);;
    }

    /**
     * APP支付
     */
    public function appPay($out_trade_no, $total_amount, $subject)
    {
        $request = new \AlipayTradeAppPayRequest ();
//        $request->setReturnUrl(Route('api.return_url'));
        $request->setNotifyUrl(Route('alipay.notify_url'));

        $bizcontent = [
            'body' => '支付',
            'out_trade_no' => $out_trade_no, //商户订单号，64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复
            'product_code' => 'QUICK_MSECURITY_PAY',
            'total_amount' => $total_amount,//订单金额
            'timeout_express' => '30m', //订单逾期支付时间 此处30分钟
            'subject' => $subject, //订单标题
        ];
        $request->setBizContent(json_encode($bizcontent, JSON_UNESCAPED_UNICODE));


        return $this->aop->sdkExecute($request);
    }


    /***
     * 订单查询
     */
    function checkOrder($out_trade_no)
    {
        $request = new \AlipayTradeQueryRequest();

        $bizcontent = [

            'out_trade_no' => $out_trade_no, //商户订单号，64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复

        ];
        $request->setBizContent(json_encode($bizcontent, JSON_UNESCAPED_UNICODE));
        return $this->aop->execute($request);
    }

    /***
     * 支付宝转账
     */
    public function transfer($out_biz_no, $payee_account, $amount)
    {
        $request = new \AlipayFundTransToaccountTransferRequest();
        $bizcontent = [
            'payee_type' => 'ALIPAY_LOGONID',
            'out_biz_no' => $out_biz_no, //商户订单号，64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复
            'payee_account' => $payee_account,
            'amount' => $amount,
            'remark' => '聚好卖余额提现'
        ];
        $request->setBizContent(json_encode($bizcontent, JSON_UNESCAPED_UNICODE));
        return $this->aop->execute($request);
    }

    /**
     * 验签方法
     * @param $arr 验签支付宝返回的信息，使用支付宝公钥。
     * @return boolean
     */
    function check($arr)
    {
        $aop = new \AopClient();
        $aop->alipayrsaPublicKey = config('alipay.alipay_public_key');
        $result = $aop->rsaCheckV1($arr, config('alipay.alipay_public_key'), "RSA2");
        return $result;
    }


    /**
     * 请确保项目文件有可写权限，不然打印不了日志。
     */
    function writeLog($text)
    {
        // $text=iconv("GBK", "UTF-8//IGNORE", $text);
        //$text = characet ( $text );
        file_put_contents("alipay_log.txt", date("Y-m-d H:i:s") . "  " . $text . "\r\n", FILE_APPEND);
    }

    /**
     * @param $out_biz_no
     * @return mixed
     * 查询转账到个人信息
     */
    function trans_money($out_biz_no)
    {
        $request = new \AlipayFundTransOrderQueryRequest ();
        $bizcontent = [
            'out_biz_no' => $out_biz_no, //商户订单号，64个字符以内、可包含字母、数字、下划线；需保证在商户端不重复
        ];
        $request->setBizContent(json_encode($bizcontent, JSON_UNESCAPED_UNICODE));
        $result = $this->aop->execute($request);
        return $result;

    }
}

