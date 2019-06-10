<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeeController extends Controller
{
    /**
     * @description:添加费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeAdd(Request $request)
    {
        return service('Fee')->feeAdd($request->all());
    }

    /**
     * @description:获得租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContractList(Request $request)
    {
        return service('Fee')->getContractList($request->all());
    }

       /**
     * @description:获得租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotice(Request $request)
    {
        return service('Fee')->sendNotice($request->all());
    }

    /**
     * @description:获得租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeList(Request $request)
    {
        return service('Fee')->feeList($request->all());
    }

    /**
     * @description:获得租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeDetail(Request $request)
    {
        return service('Fee')->feeDetail($request->all());
    }

}
