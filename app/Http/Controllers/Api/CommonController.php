<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    /**
     * @description:操作成功处理
     * @author: hkw <hkw925@qq.com>
     * @param $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($message, $data = null)
    {
        $result = array('code' => 0, 'msg' => $message);
        if ($data) $result['data'] = $data;
        return response()->json($result);
    }

    /**
     * @description:操作失败处理
     * @author: hkw <hkw925@qq.com>
     * @param $code
     * @param $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($code, $message, $data = null)
    {
        $result = array('code' => $code, 'msg' => $message);
        if ($data) $result['data'] = $data;
        return response()->json($result);
    }
}


