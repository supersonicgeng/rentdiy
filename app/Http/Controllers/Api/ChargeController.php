<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChargeController extends CommonController
{
    /**
     * @description:充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chargeList(Request $request)
    {
        return service('Charge')->chargeList($request->all());
    }


    /**
     * @description:VI[充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function vipChargeList(Request $request)
    {
        return service('Charge')->vipChargeList($request->all());
    }

    /**
     * @description:余额充值
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function charge(Request $request)
    {
        return service('Charge')->charge($request->all());
    }

    /**
     * @description:VIP充值
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function vipCharge(Request $request)
    {
        return service('Charge')->vipCharge($request->all());
    }

    /**
     * @description:充值回调
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function notify(Request $request)
    {
        return service('Charge')->notify($request->all());
    }


    /**
     * @description:充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chargedList(Request $request)
    {
        return service('Charge')->chargedList($request->all());
    }


    /**
     * @description:VI[充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function vipChargedList(Request $request)
    {
        return service('Charge')->vipChargedList($request->all());
    }
}
