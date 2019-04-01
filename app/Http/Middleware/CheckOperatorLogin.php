<?php

namespace App\Http\Middleware;

use App\Model\Operator;
use App\Model\User;
use Closure;

class CheckOperatorLogin
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
        $login_token = $request->login_token;
        $user_info = Operator::where('id',$operator_id)->first();
        if ($login_token != $user_info->login_token || time() - strtotime($user_info->login_expire_time) >7200) {
            exit(json_encode(['code'=>100,'msg'=>'pls login']));
        }
        return $next($request);
    }
}
