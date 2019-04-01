<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends CommonController
{
    //根据省获取市
    public function address(Request $request)
    {
        $input = $request->all();
        return Service('Address')->getDataOfAddress($input);
    }
}
