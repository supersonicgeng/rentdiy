<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use link1st\Easemob\App\Easemob;

class ImController extends Controller
{
    public function sendMsg(Request $request)
    {
        $send = $request->send;
        $to = $request->to;
        $message = $request->msg;
        $easemob = new Easemob();
        $res = $easemob->sendMessageText([$to],'users',$message,$send);
        dd($res);
    }
}
