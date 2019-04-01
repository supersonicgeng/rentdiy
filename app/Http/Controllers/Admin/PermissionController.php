<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class PermissionController extends CommonController
{
    public function roles(Request $request){
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Permission')->roles($input);
            return view('admin.permission.rolesAjax', ['list' => $list]);
        } else {
            return view('admin.permission.roles');
        }
    }

    public function rolesPermission($id,Request $request){
        if ($request->ajax()) {
            service('Permission')->attachPermission($id,$request->get('permissions'));
            return $this->success('授权成功', null, url('manage/roles'));
        } else {
            $role_permission = service('Permission')->rolePermission($id);
            return view('admin.permission.rolesPermission',['id'=>$id,'permissions'=>service('Permission')->permissionTree(),'role_permission'=>$role_permission]);
        }
    }

    public function users(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $list = service('Permission')->userList($input);
            return view('admin.permission.usersAjax', ['list' => $list]);
        } else {
            return view('admin.permission.users');
        }
    }

    public function usersAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name'        => 'required|max:10',
                'email'       => 'required|email',
                'phone'       => ['required', 'regex:/^(1(([3578][0-9])|(47)))\d{8}$/'],
                'password'    => 'required',
            ], [
                'name.required'        => '请填写员工姓名',
                'name.max'             => '姓名太长',
                'email.required'       => '请填写邮箱',
                'email.email'          => '邮箱格式不对',
                'phone.required'       => '请填写手机号',
                'phone.regex'          => '手机号格式不对',
                'password.required'    => '请填写密码',
            ]);
            $r = service('Permission')->userAdd($request->all());
            if ($r) {
                service('Permission')->userAttachRole($r,$request->get('role_id'));
                return $this->success('新增成功', null, url('manage/users'));
            } else {
                return $this->error(1, '新增失败');
            }
        } else {
            $role_list = service('Permission')->roles(['pageSize'=>100]);
            return view('admin.permission.usersAdd',['roles'=>$role_list]);
        }
    }

    public function usersEdit($id, Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name'        => 'required|max:10',
                'email'       => 'required|email',
                'phone'       => ['required', 'regex:/^(1(([3578][0-9])|(47)))\d{8}$/'],
                'passport_id' => 'required',
            ], [
                'name.required'        => '请填写员工姓名',
                'name.max'             => '姓名太长',
                'email.required'       => '请填写邮箱',
                'email.email'          => '邮箱格式不对',
                'phone.required'       => '请填写手机号',
                'phone.regex'          => '手机号格式不对',
                'passport_id.required' => '请绑定微信',
            ]);
            $r = service('Permission')->userEdit($id,$request->all());
            if ($r) {
                service('Permission')->userAttachRole($r,$request->get('role_id'));
                return $this->success('修改成功', null, url('manage/users'));
            } else {
                return $this->error(1, '修改失败');
            }
        } else {
            $role_list = service('Permission')->roles(['pageSize'=>100]);
            $info = service('Permission')->userInfo($id);
            return view('admin.permission.usersEdit',['roles'=>$role_list,'info'=>$info]);
        }
    }

    public function usersDel($id)
    {
        service('Permission')->userDel($id);
        return $this->success('删除成功');
    }
}
