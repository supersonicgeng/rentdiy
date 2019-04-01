<?php

namespace App\Http\Controllers\Wap;

use App\Model\Driver;
use App\Model\NoValidatePerson;
use App\Model\PlantRoute;
use App\Model\SysEvaluateItems;
use App\Model\UserEvaluate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    function __construct()
    {
        $this->middleware(['web', 'wechat.oauth']);
    }

    public function error($msg = '系统繁忙')
    {
        return view('wap.errorPage', ['msg' => $msg]);
    }
}
