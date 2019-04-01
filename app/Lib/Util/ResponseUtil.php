<?php
namespace App\Lib\Util;

class ResponseUtil
{
    public static function jsonResponse($success, $code, $message, $data=[])
    {
        $resp = response()->json([
            'Success' => $success,
            'Message' => $message,
            'ErrorCode' => $code,
            'ResultData' => $data
        ]);

        return $resp;
    }
}
