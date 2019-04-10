<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KeyController extends CommonController
{
    /**
     * @description:增加钥匙
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyAdd(Request $request)
    {
        return service('Key')->keyAdd($request->all());
    }

    /**
     * @description:归还钥匙
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyReturn(Request $request)
    {
        return service('Key')->keyReturn($request->all());
    }

    /**
     * @description:钥匙列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyList(Request $request)
    {
        return service('Key')->keyList($request->all());
    }

    /**
     * @description:钥匙编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function keyEdit(Request $request)
    {
        return service('Key')->keyEdit($request->all());
    }
}
