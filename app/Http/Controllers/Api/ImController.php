<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use link1st\Easemob\App\Easemob;

class ImController extends Controller
{
    public function sendMsg(Request $request)
    {
        $easemob = new Easemob();
        $res = $easemob->sendMessageText(['user_29'],'users','123','leo');
        dd($res);
    }
}
