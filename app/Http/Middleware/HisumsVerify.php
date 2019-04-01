<?php

namespace App\Http\Middleware;

use Closure;

class HisumsVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!service('Common')->checkHisums()){
            return response('系统异常',502);
        }
        return $next($request);
    }
}
