<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends CommonController
{
    /**
     * @description:欠款提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsNote(Request $request)
    {
        return service('Note')->getArrearsNote($request->all());
    }


    /**
     * @description:欠款14天提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFourteenDaysArrearsNote(Request $request)
    {
        return service('Note')->getFourteenDaysArrearsNote($request->all());
    }


    /**
     * @description:欠款警告通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArrearsWarning(Request $request)
    {
        return service('Note')->getArrearsWarning($request->all());
    }

    /**
     * @description:联系房东通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactLandLord(Request $request)
    {
        return service('Note')->contactLandLord($request->all());
    }


    /**
     * @description:联系房东通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactNotSignAgain(Request $request)
    {
        return service('Note')->contactNotSignAgain($request->all());
    }

    /**
     * @description:发送欠款提示通知
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNote(Request $request)
    {
        return service('Note')->sendNote($request->all());
    }
}
