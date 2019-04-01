<?php
namespace App\Lib\Util;

use Carbon\Carbon;

class TimeConvertor
{
    public static function getTimeAsFloat($hour, $minute)
    {
        $timeAsFloatFormat = $hour + round($minute * 100/60)/100;
        return $timeAsFloatFormat;
    }

    public static function getTimeAsHourAndMinute($timeAsFloatFormat)
    {
        $hourPart = floor($timeAsFloatFormat);
        $minutePart = $timeAsFloatFormat - floor($timeAsFloatFormat);
        $minute = round($minutePart * 60);
        $totalMinute = $hourPart * 60 + $minute;
        return ['hour'=>$hourPart, 'minute'=>$minute, 'totalMinute'=>$totalMinute];
    }
}
