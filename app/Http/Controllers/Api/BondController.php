<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BondController extends CommonController
{
    /**
     * @description:押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondList(Request $request)
    {
        return service('Bond')->bondList($request->all());
    }

    /**
     * @description:押金欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondArrearsList(Request $request)
    {
        return service('Bond')->bondArrearsList($request->all());
    }

    /**
     * @description:押金上缴列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondLodgedList(Request $request)
    {
        return service('Bond')->bondLodgedList($request->all());
    }


    /**
     * @description:押金退缴列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondRefundList(Request $request)
    {
        return service('Bond')->bondRefundList($request->all());
    }


    /**
     * @description:押金转移列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondTransformList(Request $request)
    {
        return service('Bond')->bondTransformList($request->all());
    }


    /**
     * @description:押金上缴日期
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBondLodgedDate(Request $request)
    {
        return service('Bond')->addBondLodgedDate($request->all());
    }

    /**
     * @description:押金上缴编号
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBondLodgedSn(Request $request)
    {
        return service('Bond')->addBondLodgedSn($request->all());
    }


    /**
     * @description:押金退缴
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundBond(Request $request)
    {
        return service('Bond')->refundBond($request->all());
    }

}
