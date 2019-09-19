<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LitigationController extends Controller
{
    /**
     * @description:添加诉讼
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addLitigation(Request $request)
    {
        return service('Litigation')->addLitigation($request->all());
    }
}
