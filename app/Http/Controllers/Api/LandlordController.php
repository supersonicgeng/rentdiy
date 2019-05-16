<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandlordController extends CommonController
{
    /**
     * @description:房东增加房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLandlordInformation(Request $request)
    {
        return service('Landlord')->addLandlordInformation($request->all());
    }


    /**
     * @description:房东修改房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editLandlordInformation(Request $request)
    {
        return service('Landlord')->editLandlordInformation($request->all());
    }


    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLandlordList(Request $request)
    {
        return service('Landlord')->getLandlordList($request->all());
    }


    /**
     * @description:房东获得房东信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLandlordInformation(Request $request)
    {
        return service('Landlord')->getLandlordInformation($request->all());
    }


    /**
     * @description:房东获得房东列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteLandlordInformation(Request $request)
    {
        return service('Landlord')->deleteLandlordInformation($request->all());
    }


    /**
     * @description:房东查看租户资料
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function watchTenementInformation(Request $request)
    {
        return service('Tenement')->watchTenementInformation($request->all());
    }


    /**
     * @description:房东查看报价列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenderList(Request $request)
    {
        return service('Landlord')->tenderList($request->all());
    }
}
