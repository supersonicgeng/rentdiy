<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TenementController extends CommonController
{
    /**
     * @description:租户增加个人信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addTenementInformation(Request $request)
    {
        return service('Tenement')->addTenementInformation($request->all());
    }

    /**
     * @description:租户修改个人信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editTenementInformation(Request $request)
    {
        return service('Tenement')->editTenementInformation($request->all());
    }

    /**
     * @description:租户获得个人信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementSelfInformation(Request $request)
    {
        return service('Tenement')->getTenementSelfInformation($request->all());
    }

    /**
     * @description:删除租户信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTenementInformation(Request $request)
    {
        return service('Tenement')->deleteTenementInformation($request->all());
    }

    /**
     * @description:房屋打分
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseScore(Request $request)
    {
        return service('Tenement')->houseScore($request->all());
    }

    /**
     * @description:房屋账单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsHouseList(Request $request)
    {
        return service('Tenement')->getArrearsHouseList($request->all());
    }
}
