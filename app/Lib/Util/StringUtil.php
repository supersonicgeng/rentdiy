<?php
namespace App\Lib\Util;

class StringUtil
{
    public static function trimAll($str)
    {
        $qian = array(" ","　","\t","\n","\r");
        $hou = array("","","","","");
        return str_replace($qian, $hou, $str);
    }

    public static function guid()
    {
    		$charid = strtoupper(md5(uniqid(mt_rand(), true)));
    		$uuid = substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12);
    		return $uuid;
  	}

    public static function checkDateStr($dateStr)
    {
        if (preg_match('/^\d{4}\-\d{2}\-\d{2}$/s', $dateStr)) {
            $dateSegs = explode('-', $dateStr);
            return checkdate(intval($dateSegs[1]), intval($dateSegs[2]), intval($dateSegs[0]));
        } else {
            return false;
        }
    }
}
