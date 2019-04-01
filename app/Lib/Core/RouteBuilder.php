<?php
namespace App\Lib\Core;

use App\Services\RouteService;
use Storage;

class RouteBuilder
{
    public static function buildWebRoutes()
    {
        if (Storage::exists('web_route.json'))
            return json_decode(Storage::get('web_route.json'), true);

        return [];
    }

    public static function buildApiRoutes()
    {
        if (Storage::exists('api_route.json'))
            return json_decode(Storage::get('api_route.json'), true);

        return [];
    }
}
