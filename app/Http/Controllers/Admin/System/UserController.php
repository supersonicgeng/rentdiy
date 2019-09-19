<?php

namespace App\Http\Controllers\Admin\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System as Requests;
use App\User;
use App\Models\System\Role;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * 用户列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->has('keyword') and $request->keyword != '') {
                $search = "%" . $request->keyword . "%";
                $query->where('username', 'like', $search);
            }
        };

        $users = User::where($where)->where('is_delete', 0)->get();
        return view('admin.system.user.index', compact('users'));
    }

    /**
     * 新增
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.system.user.create', compact('roles'));
    }

    /**
     * 保存
     * @param Requests\UserStore $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Requests\UserStore $request)
    {

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'real_name' => $request->real_name
        ]);
        $user->roles()->sync($request->role_id);
        return redirect(route('system.user.index'))->with('notice', '新增成功~');
    }

    /**
     * 编辑用户信息
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::with('roles')->find($id);
        $user_roles = $user->roles->pluck('id');
        $roles = Role::all();
        return view('admin.system.user.edit', compact('user', 'user_roles', 'roles'));
    }

    /**
     * 更新用户信息
     * @param Requests\UserUpdate $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Requests\UserUpdate $request, $id)
    {
        $user = User::find($id);

        if ($request->has('password') && $request->password != '') {


            if (!\Hash::check($request->old_password, $user->password)) {
                return back()->with('alert', '原始密码错误~');
            }
            $user->password = bcrypt($request->password);
        }

        $user->username = $request->username;
        $user->real_name = $request->real_name;
        $user->email = $request->email;
        $user->save();

        //更新用户组信息
        $user->roles()->sync($request->role_id);
        return redirect(route('system.user.index'))->with('notice', '修改成功~');
    }

    public function destroy($id)
    {
        User::where('id', $id)->update(['is_delete' => 1]);


        return ['status' => 1, 'msg' => '删除成功！'];
    }

    /***
     * 个人资料
     */
    public function person()
    {
        $admin = auth()->user();
        return view('admin.system.user.person', compact('admin'));

    }

    /***
     * 更新个人信息
     */
    public function zlUpdate(Request $request)
    {
        $admin = auth()->user();

        //定义验证规则，是一个数组
        $rules = [
            'email' => 'required|email|unique:users,email,' . $admin->id . '|max:255',
            'real_name' => 'required',
        ];

        //定义提示信息
        $messages = [
            'email.required' => '邮箱必须填',
            'real_name.required' => '真实姓名必须填',
        ];

        //创建验证器
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }


        if ($request->has('password') && $request->password != '') {

            if (!\Hash::check($request->old_password, $admin->password)) {
                return back()->with('alert', '原始密码错误~');
            }

            $admin->password = bcrypt($request->password);

            if ($request->password != $request->password_confirmation) {
                return back()->with('alert', '两次输入密码不一致~');
            }
        }

        $admin->real_name = $request->real_name;
        $admin->email = $request->email;
        $admin->save();
        return back()->with('alert', '原始密码错误~');

    }
}
