<?php

namespace App\Http\Controllers\Admin\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $permissions = Permission::get_permissions();

        return view('admin.system.permission.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::with('children')->where('parent_id', 0)->get();

        return view('admin.system.permission.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        if ($request->parent_id != 0 && !\Route::has($request->name)) {
            return ['status' => 0, 'msg' => '路由名称不存在，请修改后再保存~'];
        }

        $per = Permission::where('name',$request->name)->first();

        if($per){
            return ['status' => 0, 'msg' => '此路由已存在'];

        }

        Permission::create($request->all());
        Permission::clear();
        return ['status' => 1, 'msg' => '新增成功'];
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = Permission::with('children')->where('parent_id', 0)->get();

        $per = Permission::find($id);//获取当前编辑资料


        return view('admin.system.permission.edit', compact('permissions', 'per'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        //判断路由名称是否正确
        if ($request->parent_id != 0 && !\Route::has($request->name)) {
            return ['status' => 0, 'msg' => '路由名称不存在，请修改后再保存~'];
        }



        //如果更改所处菜单等级
        if ($request->parent_id != $permission->parent_id) {
            //检查是否有下级
            if (!Permission::check_children($id)) {
                return ['status' => 0, 'msg' => '当前菜单有子菜单，不能更改菜单等级~'];
            }
        }

        $permission->update($request->all());
        Permission::clear();//清除缓存
        return ['status' => 1, 'msg' => '更新权限成功'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {


        if (!Permission::check_children($id)) {
            return ['status' => 0, 'msg' => '当前菜单有子菜单，请先将子菜单删除后再尝试删除~'];
        }

        Permission::destroy($id);
        Permission::clear();
        return ['status' => 1, 'msg' => '删除成功~~'];
    }
}
