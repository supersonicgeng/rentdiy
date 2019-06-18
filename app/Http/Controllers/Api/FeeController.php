<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeeController extends Controller
{
    /**
     * @description:添加费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeAdd(Request $request)
    {
        return service('Fee')->feeAdd($request->all());
    }

    /**
     * @description:获得租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContractList(Request $request)
    {
        return service('Fee')->getContractList($request->all());
    }

       /**
     * @description:获得租约列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotice(Request $request)
    {
        return service('Fee')->sendNotice($request->all());
    }

    /**
     * @description:追欠款列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsList(Request $request)
    {
        return service('Fee')->arrearsList($request->all());
    }

    /**
     * @description:追欠款详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsDetail(Request $request)
    {
        return service('Fee')->arrearsDetail($request->all());
    }


    /**
 * @description:费用单列表
 * @author: syg <13971394623@163.com>
 * @param $code
 * @param $message
 * @param array|null $data
 * @return \Illuminate\Http\JsonResponse
 */
    public function feeList(Request $request)
    {
        return service('Fee')->feeList($request->all());
    }

    /**
     * @description:费用单详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function feeDetail(Request $request)
    {
        return service('Fee')->feeDetail($request->all());
    }


    /**
     * @description:现金收据列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashList(Request $request)
    {
        return service('Fee')->cashList($request->all());
    }

    /**
     * @description:现金收据详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashDetail(Request $request)
    {
        return service('Fee')->cashDetail($request->all());
    }

    /**
     * @description:现金收据冲账
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashPay(Request $request)
    {
        return service('Fee')->cashPay($request->all());
    }


    public function test(Request $request)
    {
        $file   = $request->file('file');
        return service('Fee')->test($request->all(),$file);
    }

    /**
     * @description:银行对账上传CSV文件
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bankCheck(Request $request)
    {
        return service('Fee')->bankCheck($request->all());
    }

    /**
     * @description:银行对账确认符合费用单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmMatchCheck(Request $request)
    {
        return service('Fee')->confirmMatchCheck($request->all());
    }


    /**
     * @description:银行对账余额调整
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function balanceAdjust(Request $request)
    {
        return service('Fee')->balanceAdjust($request->all());
    }
}
