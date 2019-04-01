<?php
namespace App\Lib;

class Sms
{
    protected $smsUserId;
    protected $smsAccount;
    protected $smsPassword;
    protected $smsUrl;
    protected $devMode;

    public function __construct()
    {
        $this->smsUserId = env('SMS_USERID');
        $this->smsAccount = env('SMS_ACCOUNT');
        $this->smsPassword = env('SMS_PASSWORD');
        $this->smsUrl = env('SMS_URL');
        $this->devMode = config('sms.devMode');
    }

    public function checkBalance()
    {
        $postData = array();
        $postData['userid'] = $this->smsUserId;
        $postData['account'] = $this->smsAccount;
        $postData['password'] = $this->smsPassword;
        $url = $this->smsUrl . '/sms.aspx?action=overage';
        $o ='';
        foreach ($post_data as $k=>$v) {
            $o.="$k=".urlencode($v).'&';
        }
        $postData = substr($o, 0, -1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        return $result;
    }

    public function sendSms($mobile, $content)
    {
        if (!$this->devMode) {
            $postData = array();
            $postData['userid'] = $this->smsUserId;
            $postData['account'] = $this->smsAccount;
            $postData['password'] = $this->smsPassword;

            $postData['content'] = $content;
            $postData['mobile'] = $mobile;

            $postData['sendtime'] = '';
            $url= $this->smsUrl . '/sms.aspx?action=send';
            $o = '';
            foreach ($postData as $k => $v) {
                $o .= "$k=".urlencode($v).'&';
            }
            $postData=substr($o, 0, -1);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }

    public function verifyAccount()
    {
        $postData = array();
        $postData['userid'] = $this->smsUserId;
        $postData['account'] = $this->smsAccount;
        $postData['password'] = $this->smsPassword;

        $url = $this->smsUrl . '/sms.aspx?action=overage';
        $o ='';
        foreach ($postData as $k=>$v) {
            $o .= "$k=".urlencode($v).'&';
        }
        $postData = substr($o, 0, -1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($ch);
        return $result;
    }
}
