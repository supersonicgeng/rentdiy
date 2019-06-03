<?php

namespace App\Http\Controllers\Api;

use App\Model\Config;
use App\Model\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PublicController extends CommonController
{
    /**
     * @description:发送验证码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerify(Request $request)
    {
        $account = $request->account;
        if(strpos($account,'@')){
            return service('Help')->sendMailVerify($request->all());
        }else{
            return service('Help')->sendPhoneVerify($request->all());
        }
    }

    /**
     * @description:验证验证码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVerify(Request $request)
    {
        $account = $request->account;
        $code = $request->code;
        $verify_type = $request->verify_type;
        return service('User')->verify($account,$code,$verify_type);
    }


    /**
     * @description:获得州名和州id
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegion(Request $request)
    {
        return service('Help')->getRegion();
    }

    /**
     * @description:获得市名和市id
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTa(Request $request)
    {
        $region_id = $request->region_id;
        return service('Help')->getTa($region_id);
    }


    /**
     * @description:获得市名和市id
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDistrict(Request $request)
    {
        $ta_id = $request->ta_id;
        return service('Help')->getDistrict($ta_id);
    }

    /**
     * @description:获得市名和市id
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLat(Request $request)
    {
        return service('Help')->getLat($request->all());
    }
}
