<?php
namespace App\Lib\Core;

use App\Services\RouteService;
use Storage;

class RouteSerializer
{
    public function generateRoute()
    {
        $routeService = new RouteService();
        $webRoutes = $routeService->getAllWebRoutes();

        Storage::put('web_route.json', json_encode($webRoutes));

        $apiRoutes = $routeService->getAllApiRoutes();
        Storage::put('api_route.json', json_encode($apiRoutes));
    }
}
