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

    /**
     * @description:检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectDeleteChattel(Request $request)
    {
        return service('Inspect')->inspectDeleteChattel($request->all());
    }


    /**
     * @description:检查确认
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectConfirm(Request $request)
    {
        return service('Inspect')->inspectConfirm($request->all());
    }

    /**
     * @description:检查记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectRecord(Request $request)
    {
        return service('Inspect')->inspectRecord($request->all());
    }


    /**
     * @description:检查记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordCheckDetail(Request $request)
    {
        return service('Inspect')->landlordCheckDetail($request->all());
    }



    /**
     * @description:增加维修单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIssuesBatch(Request $request)
    {
        return service('Inspect')->addIssuesBatch($request->all());
    }


    /**
     * @description:房东确认检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function landlordConfirm(Request $request)
    {
        return service('Inspect')->landlordConfirm($request->all());
    }

    /**
     * @description:检查记录
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function issueRecord(Request $request)
    {
        return service('Inspect')->issueRecord($request->all());
    }


    /**
     * @description:增加维修单
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addIssues(Request $request)
    {
        return service('Inspect')->addIssues($request->all());
    }

    /**
     * @description:添加非平台房屋检查
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectAdd(Request $request)
    {
        return service('Inspect')->unPlatInspectAdd($request->all());
    }

    /**
     * @description:非平台房屋检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectList(Request $request)
    {
        return service('Inspect')->unPlatInspectList($request->all());
    }

    /**
     * @description:非平台检查详细
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDetail(Request $request)
    {
        return service('Inspect')->unPlatInspectDetail($request->all());
    }


    /**
     * @description:非平台检查项目
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectItem(Request $request)
    {
        return service('Inspect')->unPlatInspectItem($request->all());
    }


    /**
     * @description:非平台检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectEdit(Request $request)
    {
        return service('Inspect')->unPlatInspectEdit($request->all());
    }

    /**
     * @description:非平台检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDeleteRoom(Request $request)
    {
        return service('Inspect')->unPlatInspectDeleteRoom($request->all());
    }


    /**
     * @description:非平台检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDeleteItem(Request $request)
    {
        return service('Inspect')->unPlatInspectDeleteItem($request->all());
    }


    /**
     * @description:非平台检查编辑
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unPlatInspectDeleteChattel(Request $request)
    {
        return service('Inspect')->unPlatInspectDeleteChattel($request->all());
    }
}
