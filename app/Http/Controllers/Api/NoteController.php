<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends CommonController
{
    /**
     * @description:欠款提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsNote(Request $request)
    {
        return service('Note')->getArrearsNote($request->all());
    }


    /**
     * @description:欠款14天提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFourteenDaysArrearsNote(Request $request)
    {
        return service('Note')->getFourteenDaysArrearsNote($request->all());
    }


    /**
     * @description:欠款警告通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsWarning(Request $request)
    {
        return service('Note')->getArrearsWarning($request->all());
    }

    /**
     * @description:联系房东通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactLandLord(Request $request)
    {
        return service('Note')->contactLandLord($request->all());
    }


    /**
     * @description:固定租约到期不续约通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactNotSignAgain(Request $request)
    {
        return service('Note')->contactNotSignAgain($request->all());
    }


    /**
     * @description:分租涨租通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function subletLeaseUp(Request $request)
    {
        return service('Note')->subletLeaseUp($request->all());
    }

    /**
     * @description:涨租通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaseUp(Request $request)
    {
        return service('Note')->leaseUp($request->all());
    }

    /**
     * @description:房东搬入通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordMoveIn(Request $request)
    {
        return service('Note')->landlordMoveIn($request->all());
    }


    /**
     * @description:开放式合约结束租约
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function stopRent(Request $request)
    {
        return service('Note')->stopRent($request->all());
    }

    /**
     * @description:家庭成员搬回
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function homeIn(Request $request)
    {
        return service('Note')->homeIn($request->all());
    }


    /**
     * @description:房东卖房
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function saleHouse(Request $request)
    {
        return service('Note')->saleHouse($request->all());
    }


    /**
     * @description:14天违约警告
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function fourteenDaysNote(Request $request)
    {
        return service('Note')->fourteenDaysNote($request->all());
    }


    /**
     * @description:14天违约警告
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceNote(Request $request)
    {
        return service('Note')->invoiceNote($request->all());
    }

    /**
     * @description:发送欠款提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNote(Request $request)
    {
        return service('Note')->sendNote($request->all());
    }
}
