<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends CommonController
{
    /**
     * @description:物品清单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chattelReport(Request $request)
    {
        return service('Report')->chattelReport($request->all());
    }

    /**
     * @description:物品清单详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chattelDetail(Request $request)
    {
        return service('Report')->chattelDetail($request->all());
    }

    /**
     * @description:租约到期
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentDeadLineReport(Request $request)
    {
        return service('Report')->rentDeadLineReport($request->all());
    }



    /**
     * @description:涨租列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function rentIncrementReport(Request $request)
    {
        return service('Report')->rentIncrementReport($request->all());
    }


    /**
     * @description:押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondReport(Request $request)
    {
        return service('Report')->bondReport($request->all());
    }


    /**
     * @description:欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsReport(Request $request)
    {
        return service('Report')->arrearsReport($request->all());
    }

    /**
     * @description:租客欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementReport(Request $request)
    {
        return service('Report')->tenementReport($request->all());
    }

    /**
     * @description:租客账单列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementArrearsReport(Request $request)
    {
        return service('Report')->tenementArrearsReport($request->all());
    }

    /**
     * @description:租客行为记录详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function tenementReportDetail(Request $request)
    {
        return service('Report')->tenementReportDetail($request->all());
    }

    /**
     * @description:商业费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function businessArrearsReport(Request $request)
    {
        return service('Report')->businessArrearsReport($request->all());
    }

    /**
     * @description:房屋选择
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHouseList(Request $request)
    {
        return service('Report')->getHouseList($request->all());
    }
}
