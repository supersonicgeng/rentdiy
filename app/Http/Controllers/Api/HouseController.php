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
    public function houseList(Request $request)
    {
        return service('House')->houseList($request->all());
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
     * @description:房屋主档下架
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function houseListDown(Request $request)
    {
        return service('House')->houseListDown($request->all());
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


    /**
     * @description:租户增加看房收藏
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addWatchList(Request $request)
    {
        return service('House')->addWatchList($request->all());
    }

    /**
     * @description:租户删除看房收藏
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteWatchList(Request $request)
    {
        return service('House')->deleteWatchList($request->all());
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
     * @description:获取关注主档列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWatchList(Request $request)
    {
        return service('House')->getWatchList($request->all());
    }



    /**
     * @description:获得房屋主档信息列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectSelfHouseList(Request $request)
    {
        return service('House')->selectSelfHouseList($request->all());
    }


    /**
     * @description:获取房间名称
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomName(Request $request)
    {
        return service('House')->getRoomName($request->all());
    }


    /**
     * @description:获取房间评论
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseScore(Request $request)
    {
        return service('House')->getHouseScore($request->all());
    }
}
