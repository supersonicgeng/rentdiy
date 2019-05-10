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

    /**
     * @description:检查详细
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDetail(Request $request)
    {
        return service('Inspect')->inspectDetail($request->all());
    }


    /**
     * @description:检查项目
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectItem(Request $request)
    {
        return service('Inspect')->inspectItem($request->all());
    }

    /**
     * @description:批量检查 房屋列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectGroupRoom(Request $request)
    {
        return service('Inspect')->inspectGroupRoom($request->all());
    }


    /**
     * @description:房东开始检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectCheck(Request $request)
    {
        return service('Inspect')->inspectCheck($request->all());
    }



    /**
     * @description:检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectEdit(Request $request)
    {
        return service('Inspect')->inspectEdit($request->all());
    }


    /**
     * @description:检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDeleteRoom(Request $request)
    {
        return service('Inspect')->inspectDeleteRoom($request->all());
    }


    /**
     * @description:检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDeleteItem(Request $request)
    {
        return service('Inspect')->inspectDeleteItem($request->all());
    }
}
