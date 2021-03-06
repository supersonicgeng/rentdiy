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


    /**
     * @description:服务商接单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderList(Request $request)
    {
        return service('Providers')->getOrderList($request->all());
    }


    /**
     * @description:服务商接单看房列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLookOrderList(Request $request)
    {
        return service('Providers')->getLookOrderList($request->all());
    }


    /**
     * @description:服务商接单租户调查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementOrderList(Request $request)
    {
        return service('Providers')->getTenementOrderList($request->all());
    }



    /**
     * @description:服务商接单房屋检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInspectOrderList(Request $request)
    {
        return service('Providers')->getInspectOrderList($request->all());
    }



    /**
     * @description:服务商接单维修列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRepairOrderList(Request $request)
    {
        return service('Providers')->getRepairOrderList($request->all());
    }



    /**
     * @description:服务商接单房屋诉讼列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLitigationOrderList(Request $request)
    {
        return service('Providers')->getLitigationOrderList($request->all());
    }

    /**
     * @description:服务商接单房屋诉讼列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLookOrderDetail(Request $request)
    {
        return service('Providers')->getLookOrderDetail($request->all());
    }

    /**
     * @description:服务商接单房屋诉讼列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementOrderDetail(Request $request)
    {
        return service('Providers')->getTenementOrderDetail($request->all());
    }



    /**
     * @description:服务商接单房屋诉讼列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRepairOrderDetail(Request $request)
    {
        return service('Providers')->getRepairOrderDetail($request->all());
    }


    /**
     * @description:服务商接单房屋诉讼列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLitigationOrderDetail(Request $request)
    {
        return service('Providers')->getLitigationOrderDetail($request->all());
    }


    /**
     * @description:服务商接单房屋诉讼列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementReview(Request $request)
    {
        return service('Providers')->tenementReview($request->all());
    }



    /**
     * @description:服务商接单房屋诉讼列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookOrder(Request $request)
    {
        return service('Providers')->lookOrder($request->all());
    }


    /**
     * @description:服务商给房东打分
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordScore(Request $request)
    {
        return service('Providers')->landlordScore($request->all());
    }
}
