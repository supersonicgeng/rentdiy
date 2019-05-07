<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InspectController extends Controller
{
    /**
     * @description:新建检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectAdd(Request $request)
    {
        return service('Inspect')->inspectAdd($request->all());
    }


    /**
     * @description:检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectList(Request $request)
    {
        return service('Inspect')->inspectList($request->all());
    }

}
