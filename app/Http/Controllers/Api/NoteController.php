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
