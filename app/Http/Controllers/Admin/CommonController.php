<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.shop');
    }

    /**
     * @description:操作成功处理
     * @author: hkw <hkw925@qq.com>
     * @param $message
     * @param null $data
     * @param string $url
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($message, $data = null, $url = ''){
        $result = array('code' => 0, 'msg' => $message, 'url' => $url);
        if ($data) $result['data'] = $data;
        return response()->json($result);
    }

    /**
     * @description:操作失败处理
     * @author: hkw <hkw925@qq.com>
     * @param $code
     * @param $message
     * @param null $data
     * @param string $url
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($code, $message, $data = null, $url = ''){
        $result = array('code' => $code, 'msg' => $message, 'url' => $url);
        if ($data) $result['data'] = $data;
        return response()->json($result);
    }


}
