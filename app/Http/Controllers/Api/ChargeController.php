<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChangeController extends CommonController
{
    /**
     * @description:充值列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function chargeList(Request $request)
    {
        return service('Charge')->chargeList($request->all());
    }

}
