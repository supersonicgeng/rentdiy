<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/30
 * Time: 10:27
 */

namespace App;


use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['name','display_name','description','type'];
    public static $ROLE_TYPE = [
        1=>'营运管理部',
        2=>'车辆管理部',
        3=>'人事总务部'
    ];

    public static $DRIVER_CAPTAIN = 'a4';
}