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
    public function orderList(Request $request)
    {
        return service('Landlord')->orderList($request->all());
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


    /**
     * @description:房东确认报价
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenderAccept(Request $request)
    {
        return service('Landlord')->tenderAccept($request->all());
    }



    /**
     * @description:房东中止订单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderStop(Request $request)
    {
        return service('Landlord')->orderStop($request->all());
    }


    /**
     * @description:获得租户列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementList(Request $request)
    {
        return service('Landlord')->getTenementList($request->all());
    }



    /**
     * @description:租户行为记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementNote(Request $request)
    {
        return service('Landlord')->tenementNote($request->all());
    }

    /**
     * @description:租户管理
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementManage(Request $request)
    {
        return service('Landlord')->tenementManage($request->all());
    }

    /**
     * @description:租约生成时获取租户信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTenementInfo(Request $request)
    {
        return service('Landlord')->getTenementInfo($request->all());
    }

    /**
     * @description:获取服务商列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersList(Request $request)
    {
        return service('Landlord')->getProvidersList($request->all());
    }

    /**
     * @description:获取服务商详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProvidersDetail(Request $request)
    {
        return service('Landlord')->getProvidersDetail($request->all());
    }


    /**
     * @description:折线统计
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLine(Request $request)
    {
        return service('Landlord')->getLine($request->all());
    }

    /**
     * @description:欠租率
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsRate(Request $request)
    {
        return service('Landlord')->arrearsRate($request->all());
    }


    /**
     * @description:空置率
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function vacancyRate(Request $request)
    {
        return service('Landlord')->vacancyRate($request->all());
    }

    /**
     * @description:租金收取
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentReceive(Request $request)
    {
        return service('Landlord')->rentReceive($request->all());
    }

    /**
     * @description:租金生成
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsSend(Request $request)
    {
        return service('Landlord')->arrearsSend($request->all());
    }


    /**
     * @description:日历计数
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskNum(Request $request)
    {
        return service('Landlord')->taskNum($request->all());
    }
}
