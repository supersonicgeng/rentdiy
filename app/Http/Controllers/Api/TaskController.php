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

    /**
     * @description:房东通知列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteTaskDayDetail(Request $request)
    {
        return service('Task')->noteTaskDayDetail($request->all());
    }


    /**
     * @description:房东检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectTaskDayDetail(Request $request)
    {
        return service('Task')->inspectTaskDayDetail($request->all());
    }

    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondTaskDayDetail(Request $request)
    {
        return service('Task')->bondTaskDayDetail($request->all());
    }



    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsTaskDayDetail(Request $request)
    {
        return service('Task')->arrearsTaskDayDetail($request->all());
    }

    /**
     * @description:房东涨租列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function increaseTaskDayDetail(Request $request)
    {
        return service('Task')->increaseTaskDayDetail($request->all());
    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function applicationTaskDayDetail(Request $request)
    {
        return service('Task')->applicationTaskDayDetail($request->all());
    }


    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteProviderTaskDayDetail(Request $request)
    {
        return service('Task')->noteProviderTaskDayDetail($request->all());
    }

    /**
     * @description:房东涨租列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsProvidersTaskDayDetail(Request $request)
    {
        return service('Task')->arrearsProvidersTaskDayDetail($request->all());
    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceProvidersTaskDayDetail(Request $request)
    {
        return service('Task')->invoiceProvidersTaskDayDetail($request->all());
    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function newTaskDayDetail(Request $request)
    {
        return service('Task')->newTaskDayDetail($request->all());
    }


    /**
     * @description:房东通知列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteTaskHourDetail(Request $request)
    {
        return service('Task')->noteTaskHourDetail($request->all());
    }


    /**
     * @description:房东检查列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function inspectTaskHourDetail(Request $request)
    {
        return service('Task')->inspectTaskHourDetail($request->all());
    }

    /**
     * @description:房东押金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function bondTaskHourDetail(Request $request)
    {
        return service('Task')->bondTaskHourDetail($request->all());
    }



    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsTaskHourDetail(Request $request)
    {
        return service('Task')->arrearsTaskHourDetail($request->all());
    }

    /**
     * @description:房东涨租列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function increaseTaskHourDetail(Request $request)
    {
        return service('Task')->increaseTaskHourDetail($request->all());
    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function applicationTaskHourDetail(Request $request)
    {
        return service('Task')->applicationTaskHourDetail($request->all());
    }


    /**
     * @description:房东租金列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function noteProviderTaskHourDetail(Request $request)
    {
        return service('Task')->noteProviderTaskHourDetail($request->all());
    }

    /**
     * @description:房东涨租列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrearsProvidersTaskHourDetail(Request $request)
    {
        return service('Task')->arrearsProvidersTaskHourDetail($request->all());
    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function invoiceProvidersTaskHourDetail(Request $request)
    {
        return service('Task')->invoiceProvidersTaskHourDetail($request->all());
    }


    /**
     * @description:房东申请列表
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function newTaskHourDetail(Request $request)
    {
        return service('Task')->newTaskHourDetail($request->all());
    }

    /**
     * @description:完成任务
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function finishTask(Request $request)
    {
        return service('Task')->finishTask($request->all());
    }


    /**
     * @description:修改任务时间
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function extensionTask(Request $request)
    {
        return service('Task')->extensionTask($request->all());
    }



    /**
     * @description:未完成任务
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsolveTask(Request $request)
    {
        return service('Task')->unsolveTask($request->all());
    }
}
