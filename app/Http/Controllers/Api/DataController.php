<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends CommonController
{
    public function adminInfo(){
        $result = service('Static')->indexData();
        return $this->success('统计数据',$result);
    }
}
