<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RentController extends CommonController
{
    /**
     * @description:租户申请租房
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplication(Request $request)
    {
        return service('Rent')->rentApplication($request->all());
    }

    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function outRentApplicationAdd(Request $request)
    {
        return service('Rent')->outRentApplication($request->all());
    }

    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function outRentApplicationInformation(Request $request)
    {
        return service('Rent')->outRentApplicationInformation($request->all());
    }

    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplicationOutList(Request $request)
    {
        return service('Rent')->rentApplicationOutList($request->all());
    }

    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplicationOutEdit(Request $request)
    {
        return service('Rent')->rentApplicationOutEdit($request->all());
    }


    /**
     * @description:租户租房申请（非本平台）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentApplicationOutDelete(Request $request)
    {
        return service('Rent')->rentApplicationOutDelete($request->all());
    }

    /**
     * @description:租户租房申请列表（房东查看）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentHouseApplicationList(Request $request)
    {
        return service('Rent')->rentHouseApplicationList($request->all());
    }

    /**
     * @description:租户租房申请列表（租户查看）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationList(Request $request)
    {
        return service('Rent')->rentTenementApplicationList($request->all());
    }

    /**
     * @description:租户租房申请详情（租户查看）
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationDetail(Request $request)
    {
        return service('Rent')->rentTenementApplicationDetail($request->all());
    }


    /**
     * @description:添加租约
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactAdd(Request $request)
    {
        return service('Rent')->rentContactAdd($request->all());
    }
}
