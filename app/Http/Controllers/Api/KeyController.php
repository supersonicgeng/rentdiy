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
}
