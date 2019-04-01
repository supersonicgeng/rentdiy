<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 16:33
 */

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Permission;

class RouteService
{
    public function getAdminRoutes()
    {
        if (Storage::exists('web_route.json')) {
            return json_decode(Storage::get('web_route.json'), true);
        }
        return [];
    }

    /**
     * @description:更新路由
     * @author: hkw <hkw925@qq.com>
     */
    public function updateRouteCache()
    {
        $route  = new Permission();
        $routes = $route->all()->toArray();
        Storage::put('web_route.json', json_encode($routes));
    }
}