<?php

namespace App\Http\Middleware;

use App\Model\Driver;
use App\Model\PlantRoute;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class PassportAuthenticated
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
        if(!session('wechat.oauth_user')){
            return redirect('/index/index');
        }
        $passport = service('Passport')->info(session('wechat.oauth_user'));
        $request->attributes->add(['user'=>$passport]);
        return $next($request);
    }
}
