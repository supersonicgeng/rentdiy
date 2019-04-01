<?php

namespace App\Http\Controllers\Wap;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    function __construct()
    {
        $this->middleware(['web', 'wechat.oauth','passport']);
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

    /**
     * display方法
     * @param string $page
     * @param array $param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function display($param = [],$page = ''){
        if($page){
            return view('wap.'.request()->get('agent')->theme.'.'.$page,$param);
        }else{
            return view('wap.'.request()->get('agent')->theme.'.'.getCurrentControllerName().'.'.getCurrentMethodName(),$param);
        }
    }
}
