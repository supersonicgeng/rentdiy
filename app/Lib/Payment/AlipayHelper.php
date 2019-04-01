<?php
namespace App\Lib\Payment;

class AlipayHelper
{
    private $rsaPublicKeyFilePath = null;
    private $rsaPrivateKeyFilePath = null;
    private $rsaAlipayPublicKeyFilePath = null;

    public function checkResponseSign(Array $params)
    {
        return $this->verify($this->buildAlipaySignData($params),
            $params['sign'],
            $this->getRsaAlipayPublicKeyFilePath(),
            $params['sign_type']
        );
    }

    public function checkResponseAppId($appId)
    {
        return config('alipay.app_id') == $appId;
    }

    public function checkResponseSellerId($sellerId)
    {
        return config('alipay.seller_id') == $sellerId;
    }

    public function checkResponseSellerEmail($sellerEmail)
    {
        return config('alipay.seller_email') == $sellerEmail;
    }

    public function checkResponseSubject($subject)
    {
        return '优优照护' == $subject;
    }

    public function checkTradeSuccess($tradeStatus)
    {
        return 'TRADE_SUCCESS' == $tradeStatus;
    }

    private function getRsaAlipayPublicKeyFilePath() {
        $this->rsaAlipayPublicKeyFilePath = storage_path().'/app/public/app_alipay_public_key.pem';
        return $this->rsaAlipayPublicKeyFilePath;
    }

    private function getRsaPublicKeyFilePath() {
        $this->rsaPublicKeyFilePath = storage_path().'/app/public/app_public_key.pem';
        return $this->rsaPublicKeyFilePath;
    }

    private function getRsaPrivateKeyFilePath() {
        $this->$rsaPrivateKeyFilePath = storage_path().'/app/public/app_private_key.pem';
        return $this->$rsaPrivateKeyFilePath;
    }

    private function buildAlipaySignData(Array $params)
    {
        $responseText = '';
        if (isset($params['app_id'])) {
            $responseText .= 'app_id='.$params['app_id'].'&';
        }
        if (isset($params['body'])) {
            $responseText .= 'body='.$params['body'].'&';
        }
        if (isset($params['buyer_email'])) {
            $responseText .= 'buyer_email='.$params['buyer_email'].'&';
        }
        if (isset($params['buyer_id'])) {
            $responseText .= 'buyer_id='.$params['buyer_id'].'&';
        }
        if (isset($params['buyer_logon_id'])) {
            $responseText .= 'buyer_logon_id='.$params['buyer_logon_id'].'&';
        }
        if (isset($params['buyer_pay_amount'])) {
            $responseText .= 'buyer_pay_amount='.$params['buyer_pay_amount'].'&';
        }
        if (isset($params['charset'])) {
            $responseText = 'charset='.$params['charset'].'&';
        }
        if (isset($params['discount'])) {
            $responseText .= 'discount='.$params['discount'].'&';
        }
        if (isset($params['fund_bill_list'])) {
            $responseText .= 'fund_bill_list='.$params['fund_bill_list'].'&';
        }
        if (isset($params['gmt_close'])) {
            $responseText .= 'gmt_close='.$params['gmt_close'].'&';
        }
        if (isset($params['gmt_create'])) {
            $responseText .= 'gmt_create='.$params['gmt_create'].'&';
        }
        if (isset($params['gmt_payment'])) {
            $responseText .= 'gmt_payment='.$params['gmt_payment'].'&';
        }
        if (isset($params['gmt_refund'])) {
            $responseText .= 'gmt_refund='.$params['gmt_refund'].'&';
        }
        if (isset($params['invoice_amount'])) {
            $responseText .= 'invoice_amount='.$params['invoice_amount'].'&';
        }
        if (isset($params['is_total_fee_adjust'])) {
            $responseText .= 'is_total_fee_adjust='.$params['is_total_fee_adjust'].'&';
        }
        if (isset($params['notify_id'])) {
            $responseText .= 'notify_id='.$params['notify_id'].'&';
        }
        if (isset($params['notify_time'])) {
            $responseText .= 'notify_time='.$params['notify_time'].'&';
        }
        if (isset($params['notify_type'])) {
            $responseText .= 'notify_type='.$params['notify_type'].'&';
        }
        if (isset($params['out_biz_no'])) {
            $responseText .= 'out_biz_no='.$params['out_biz_no'].'&';
        }
        if (isset($params['out_trade_no'])) {
            $responseText .= 'out_trade_no='.$params['out_trade_no'].'&';
        }
        if (isset($params['payment_type'])) {
            $responseText .= 'payment_type='.$params['payment_type'].'&';
        }
        if (isset($params['point_amount'])) {
            $responseText .= 'point_amount='.$params['point_amount'].'&';
        }
        if (isset($params['price'])) {
            $responseText .= 'price='.$params['price'].'&';
        }
        if (isset($params['quantity'])) {
            $responseText .= 'quantity='.$params['quantity'].'&';
        }
        if (isset($params['receipt_amount'])) {
            $responseText .= 'receipt_amount='.$params['receipt_amount'].'&';
        }
        if (isset($params['refund_fee'])) {
            $responseText .= 'refund_fee='.$params['refund_fee'].'&';
        }
        if (isset($params['seller_email'])) {
            $responseText .= 'seller_email='.$params['seller_email'].'&';
        }
        if (isset($params['seller_id'])) {
            $responseText .= 'seller_id='.$params['seller_id'].'&';
        }
        if (isset($params['send_back_fee'])) {
            $responseText .= 'send_back_fee='.$params['send_back_fee'].'&';
        }
        if (isset($params['subject'])) {
            $responseText .= 'subject='.$params['subject'].'&';
        }
        if (isset($params['total_amount'])) {
            $responseText .= 'total_amount='.$params['total_amount'].'&';
        }
        if (isset($params['total_fee'])) {
            $responseText .= 'total_fee='.$params['total_fee'].'&';
        }
        if (isset($params['trade_no'])) {
            $responseText .= 'trade_no='.$params['trade_no'].'&';
        }
        if (isset($params['trade_status'])) {
            $responseText .= 'trade_status='.$params['trade_status'].'&';
        }
        if (isset($params['use_coupon'])) {
            $responseText .= 'use_coupon='.$params['use_coupon'].'&';
        }
        if (isset($params['version'])) {
            $responseText .= 'version='.$params['version'].'&';
        }
        $responseText = substr($responseText, 0, strlen($responseText)-1);

        return $responseText;
    }

    private function verify($data, $sign, $rsaPublicKeyFilePath, $signType = 'RSA')
    {
		//读取公钥文件
		$pubKey = file_get_contents($rsaPublicKeyFilePath);

		//转换为openssl格式密钥
		$res = openssl_get_publickey($pubKey);
		($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');
		//调用openssl内置方法验签，返回bool值

		if ("RSA2" == $signType) {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
		} else {
			$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		}

		//释放资源
		openssl_free_key($res);

		return $result;
	}

    public function responseToNoticeAlipaySuccess()
    {
        echo "success";
    }
}
