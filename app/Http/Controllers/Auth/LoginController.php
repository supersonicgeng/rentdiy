<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    public function username()
    {
        return 'username';
    }


    protected function validateLogin(Request $request)
    {

        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/admin/login');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->is_delete == 1) {

            $this->guard()->logout();

            $request->session()->invalidate();

            return back()->with('alert','用户不存在或已删除~~');
        }

        $user->is_lock = 0;
        $user->save();
    }


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
