<?php
namespace App\Lib\Core;

use Illuminate\Http\Request;

class Base
{
    public static $APPTYPE_IOS = 1;
    public static $APPTYPE_ANDROID = 2;
    public static $APPTYPE_WEIXIN = 3;
    public static $APPTYPE_APPLET = 4;

    public static function getApiDefaultParams(Request $request)
    {
        $urlParts = $request->segments();

        if (count($urlParts) <= 3) {
            return false;
        }

        return [
            'api' => $urlParts[0],
            'appCode' => $urlParts[1],
            'appType' => $urlParts[2],
        ];
    }
}
