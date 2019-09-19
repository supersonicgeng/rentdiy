<?php

namespace App\Http\Middleware;

use Closure;

class CrossHttp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        header('Access-Control-Allow-Origin:*');
        // 响应类型
        header('Access-Control-Allow-Methods:*');
        // 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');

        return $next($request);
    }
}
