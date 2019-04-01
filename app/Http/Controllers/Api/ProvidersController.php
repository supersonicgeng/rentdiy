<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvidersController extends CommonController
{
    /**
     * @description:服务商增加服务商主体信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProvidersInformation(Request $request)
    {
        return service('Providers')->addProvidersInformation($request->all());
    }

    /**
     * @description:服务商修改服务商主体信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProvidersInformation(Request $request)
    {
        return service('Providers')->editProvidersInformation($request->all());
    }



    /**
     * @description:服务商获得服务商主体列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersSelfList(Request $request)
    {
        return service('Providers')->getProvidersSelfList($request->all());
    }


    /**
     * @description:服务商获得服务商信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersInformation(Request $request)
    {
        return service('Providers')->getProvidersInformation($request->all());
    }



    /**
     * @description:删除服务商主体
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProvidersInformation(Request $request)
    {
        return service('Providers')->deleteProvidersInformation($request->all());
    }
}
