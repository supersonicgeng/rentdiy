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
}
