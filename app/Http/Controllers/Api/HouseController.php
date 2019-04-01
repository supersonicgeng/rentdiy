<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HouseController extends CommonController
{
    /**
     * @description:添加房屋主档
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addHouseList(Request $request)
    {
        return service('House')->addHouseList($request->all());
    }


    /**
     * @description:获取房屋主档列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseList(Request $request)
    {
        return service('House')->getHouseList($request->all());
    }

    /**
     * @description:获取房屋主档信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseDetail(Request $request)
    {
        return service('House')->houseDetail($request->all());
    }

    /**
     * @description:房屋主档上架
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseListPut(Request $request)
    {
        return service('House')->houseListPut($request->all());
    }

    /**
     * @description:修改房屋主档
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editHouseList(Request $request)
    {
        return service('House')->editHouseList($request->all());
    }

    /**
     * @description:获取房屋主档信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseInfomation(Request $request)
    {
        return service('House')->getHouseInfomation($request->all());
    }



    /**
     * @description:获得房屋主档信息列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSelfHouseList(Request $request)
    {
        return service('House')->getSelfHouseList($request->all());
    }


    /**
     * @description:获得房屋主档信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseGroupDetail(Request $request)
    {
        return service('House')->getHouseGroupDetail($request->all());
    }

    /**
     * @description:删除房屋主档
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteHouseList(Request $request)
    {
        return service('House')->deleteHouseList($request->all());
    }
}
