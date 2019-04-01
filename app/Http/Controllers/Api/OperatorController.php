<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OperatorController extends Controller
{
    /**
     * @description:增加操作员信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOperatorInformation(Request $request)
    {
        return service('Operator')->addOperatorInformation($request->all());
    }


    /**
     * @description:操作员登陆
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        return service('Operator')->login($request->all());
    }



    /**
     * @description:编辑操作员信息
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function editOperatorInformation(Request $request)
    {
        return service('Operator')->editOperatorInformation($request->all());
    }

    /**
     * @description: 查询操作员账号
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkOperatorAccount(Request $request)
    {
        return service('Operator')->checkOperatorAccount($request->all());
    }


    /**
     * @description: 获得操作员列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOperatorList(Request $request)
    {
        return service('Operator')->getOperatorList($request->all());
    }


    /**
     * @description: 修改操作员是否禁用
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeOperatorStatus(Request $request)
    {
        return service('Operator')->changeOperatorStatus($request->all());
    }

}
