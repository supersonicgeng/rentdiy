<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\System\Permission;
use Auth, Gate, Route;


class Sidebar
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
        view()->share([
            'systems' => config('admin.systems'),
        ]);
        $this->menus();
        $this->active_menu();

        return $next($request);
    }

    /**
     * 自动选择菜单
     */
    private function active_menu()
    {
        $name = Route::currentRouteName();
        $parent_menu = $children_menu = '';

        //如果不是后台首页， 首页直接为 ''
        if ($name != 'admin.index') {
            $permission = Permission::with('parent')->where('name', $name)->first();

            //判断当前是二级还是三级
            if ($permission->parent->parent_id == 0) {
                $parent_menu = $permission->parent->name;
                $children_menu = $permission->name;
            } else {
                $parent_menu = $permission->parent->parent->name;
                $children_menu = $permission->parent->name;
            }
        }
        view()->share(compact('parent_menu', 'children_menu'));
    }

    /**
     * 所有菜单
     */
    private function menus()
    {
        $permissions = Permission::get_children();

        //如果是超级管理员，则拥有所有菜单。否则自动获取该用户拥有权限的菜单。
        $menus = Auth::user()->hasRole('Administrator') ? $permissions : $this->get_menus($permissions);


        view()->share('menus', $menus);
    }

    /**
     * 获取有权限访问的菜单
     * @param $permissions
     * @return mixed
     */
    private function get_menus($permissions)
    {
        foreach ($permissions as $key => $permission) {
            if (Gate::denies($permission->name) or $permission->is_show == 0) {
                unset($permissions[$key]);
                continue;
            }
            foreach ($permission->children as $k => $children) {
                if (Gate::denies($children->name) or $permission->is_show == 0) {
                    unset($permissions[$key]['children'][$k]);
                }
            }
        }
        return $permissions;
    }


}
