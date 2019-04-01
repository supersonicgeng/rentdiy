<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 10:27
 */

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    public static $TOP_CATE_PID = 0;

    public static $TYPE_GET = 2;
    public static $TYPE_POST = 3;
    public static $TYPE_ANY = 1;
    public static $TYPE_PUT = 4;
    public static $TYPE_DELETE = 5;
}