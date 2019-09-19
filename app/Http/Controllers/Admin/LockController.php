<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LockController extends Controller
{
    /***
     * 锁定操作
     */
    public function lock()
    {
        $redirect = \URL::previous();

        $user = auth()->user();
        $user->is_lock = 1;
        $user->save();

        session()->put('logout_url', $redirect);

        return redirect(route('admin.lockView'));
    }

    /**8
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 锁定登陆页面
     */
    public function lockView()
    {
        if (auth()->user()->is_lock == 0) {
            $v = session('logout_url');

            if ($v != '') {
                return redirect($v);
            }

            return redirect(route('admin.index'));
        }

        return view('admin.lock');
    }

    /***
     * 锁屏后登陆
     */
    public function login(Request $request)
    {
        if ($request->password == '') {
            return back()->with('alert', '请输入密码~');
        }

        if ($request->has('password') && $request->password != '') {

            if (!\Hash::check($request->password, auth()->user()->password)) {
                return back()->with('alert', '密码错误~');
            }
        }
        auth()->user()->is_lock = 0;
        auth()->user()->save();

        $v = session('logout_url');

        if ($v != '') {
            return redirect($v);
        }

        return redirect(route('admin.index'));
    }
}
