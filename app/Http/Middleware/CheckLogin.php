<?php

namespace App\Http\Middleware;

use App\Model\User;
use Closure;

class CheckLogin
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
        $operator_id = $request->operator_id;

        $user_id = $request->user_id;
        $login_token = $request->login_token;
        $user_info = User::where('id',$user_id)->first();
        if ($login_token != $user_info->login_token || time() - strtotime($user_info->login_expire_time) >7200) {
            exit(json_encode(['code'=>100,'msg'=>'pls login']));
        }
        return $next($request);
    }
}
