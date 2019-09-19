<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use http\Env;
use Illuminate\Support\Facades\DB;
use Route, URL, Auth, Gate;

class Role
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


        if (auth()->user()->is_lock == 1) {
            return redirect(route('admin.lockView'));
        }

        if (Auth::user()->hasRole('Administrator')) {

            $this->userLog($request);
            return $next($request);
        }

        if (Route::currentRouteName() == 'admin.index') {
            $this->userLog($request);
            return $next($request);
        }

        $previousUrl = URL::previous();
        if (Gate::denies(Route::currentRouteName())) {
            if ($request->ajax() && ($request->getMethod() != 'GET')) {
                return response()->json([
                    'status' => 0,
                    'code' => 403,
                    'msg' => 'You do not have permission to perform this operation~'
                ]);
            } else {
                return response()->view('admin.errors.403', compact('previousUrl'));
            }
        }

        $this->userLog($request);
        return $next($request);
    }


    private function userLog($request)
    {

        // dd($request);
        $data = [];
        $data['user_id'] = auth()->user()->id;
        $data['user_name'] = auth()->user()->username;
        $data["action"] = $this->actionTrans($request->method());
        $data["router_des"] = DB::table("permissions")->where("name", Route::currentRouteName())->first()->label ?? "HomeIndex";

        $data['req_param'] = json_encode($request->all());
        $data['req_url'] = $request->path();
        $data["created_at"] = Carbon::now();
        $data["updated_at"] = Carbon::now();

        DB::table("user_logs")->insert($data);

    }


    private function actionTrans($method)
    {
        switch ($method) {
            case 'GET':
                return 'Read';
                break;
            case 'POST':
                return 'Save';
                break;
            case 'CREATE':
                return 'Add View';
                break;
            case 'STORE':
                return 'Store ';
                break;
            case 'DELETE':
                return 'Delete';
                break;
            case 'DESTROY':
                return 'Delete';
                break;
            case 'EDIT':
                return 'Edit View';
                break;
            case 'PUT':
                return 'Update';
                break;
            case 'PATCH':
                return 'Update';
                break;

        }

    }


}