<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvidersMarketController extends CommonController
{
    /**
     * @description:添加订单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordOrderAdd(Request $request)
    {
        return service('ProvidersMarket')->landlordOrderAdd($request->all());
    }


    /**
     * @description:获得订单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderList(Request $request)
    {
        return service('ProvidersMarket')->getOrderList($request->all());
    }

    /**
     * @description:获得订单详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderDetail(Request $request)
    {
        return service('ProvidersMarket')->getOrderDetail($request->all());
    }


    /**
     * @description:订单报价
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenderOrder(Request $request)
    {
        return service('ProvidersMarket')->tenderOrder($request->all());
    }

    /**
     * @description:订单评分
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderScore(Request $request)
    {
        return service('ProvidersMarket')->orderScore($request->all());
    }


    /**
     * @description:订单报价
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenderRepairOrder(Request $request)
    {
        return service('ProvidersMarket')->tenderRepairOrder($request->all());
    }
}
