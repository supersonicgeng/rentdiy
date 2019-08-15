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

}
