<?php
namespace App\Lib\Secure;

use App\Lib\Util\CurlRequest;

class CaptchaValidator
{
    public function validateVerifyCode($authKey)
    {
        $CurlRequest = new CurlRequest();

        $captchaVerifyUrl = config('captcha.verify_url');
        $captchaVerifyApiKey = config('captcha.verify_api_key');

        $data = array(
            'api_key' => $captchaVerifyApiKey,
            'response' => $authKey,
        );

        $validateResponse = json_decode($CurlRequest->httpPost($captchaVerifyUrl, $data),
            true);

        return $validateResponse['error'] == 0 &&
            $validateResponse['res'] == 'success';
    }
}
