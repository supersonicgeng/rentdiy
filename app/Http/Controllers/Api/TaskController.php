<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    /**
     * @description:月列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListMonth(Request $request)
    {
            return service('Task')->taskListMonth($request->all());
    }


    /**
     * @description:周列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListWeek(Request $request)
    {
        return service('Task')->taskListWeek($request->all());
    }


    /**
     * @description:日详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListDayDetail(Request $request)
    {
        return service('Task')->taskListDayDetail($request->all());
    }


    /**
     * @description:日列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListDay(Request $request)
    {
        return service('Task')->taskListDay($request->all());
    }


    /**
     * @description:小时详情
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskListHourDetail(Request $request)
    {
        return service('Task')->taskListHourDetail($request->all());
    }

    /**
     * @description:新任务
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function newTask(Request $request)
    {
        return service('Task')->newTask($request->all());
    }
}
