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
        return service('Rent')->outRentApplicationAdd($request->all());
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
     * @description:同意申请
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationAgree(Request $request)
    {
        return service('Rent')->rentTenementApplicationAgree($request->all());
    }


    /**
     * @description:备份申请
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationBackup(Request $request)
    {
        return service('Rent')->rentTenementApplicationBackup($request->all());
    }



    /**
     * @description:拒绝申请
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementApplicationReject(Request $request)
    {
        return service('Rent')->rentTenementApplicationReject($request->all());
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

    /**
     * @description:租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactList(Request $request)
    {
        return service('Rent')->rentContactList($request->all());
    }


    /**
     * @description:租约详细
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContractDetail(Request $request)
    {
        return service('Rent')->rentContractDetail($request->all());
    }

    /**
     * @description:租约生效
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentContactEffect(Request $request)
    {
        return service('Rent')->rentContactEffect($request->all());
    }

    /**
     * @description:租户查看租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentTenementContractList(Request $request)
    {
        return service('Rent')->rentTenementContractList($request->all());
    }

    /**
     * @description:查看证件
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewTenementInfo(Request $request)
    {
        return service('Rent')->viewTenementInfo($request->all());
    }

    /**
     * @description:租户打分
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementScore(Request $request)
    {
        return service('Rent')->tenementScore($request->all());
    }


    /**
     * @description:租金调整
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeRentFee(Request $request)
    {
        return service('Rent')->changeRentFee($request->all());
    }


    /**
     * @description:租约中止
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentSuspend(Request $request)
    {
        return service('Rent')->rentSuspend($request->all());
    }


    /**
     * @description:租金中止确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentSuspendSure(Request $request)
    {
        return service('Rent')->rentSuspendSure($request->all());
    }


    /**
     * @description:租约诉讼
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentLitigation(Request $request)
    {
        return service('Rent')->rentLitigation($request->all());
    }

    /**
     * @description:租约打印
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contractPrint(Request $request)
    {
        return service('Rent')->contractPrint($request->all());
    }

    /**
     * @description:市场租金
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function marketRentFee(Request $request)
    {
        return service('Rent')->marketRentFee($request->all());
    }
}
