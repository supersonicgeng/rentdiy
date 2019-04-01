<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/13 0013
 * Time: 下午 3:32
 */

namespace App\Services;


use App\Lib\Util\QueryPager;
use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\DB;

class PermissionService extends CommonService
{
    /**
     * @description:角色列表
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     * @return mixed
     */
    public function roles(array $input){
        $role = new Role();
        if (@$input['display_name']) {
            $role = $role->where('display_name', 'like', '%' . $input['display_name'] . '%');
        }
        $role = new QueryPager($role);
        return $role->getPage($input, 'id');
    }

    /**
     * @description:权限树
     * @author: hkw <hkw925@qq.com>
     * @param int $pid
     * @return array
     */
    public function permissionTree($pid = 0){
        $permission = new Permission();
        $first = $permission->where(['pid'=>$pid])->orderBy('sort','desc')->orderBy('id','asc')->get()->toArray();
        foreach($first as $k=>$v){
            $second = $permission->where(['pid'=>$v['id']])->orderBy('sort','desc')->orderBy('id','asc')->get()->toArray();
            if(!empty($second)){
                foreach($second as $v1){
                    $third = $permission->where(['pid'=>$v1['id']])->orderBy('sort','desc')->orderBy('id','asc')->get()->toArray();
                    if($third){
                        $second = array_merge($second,$third);
                    }
                }
            }
            $first[$k]['_child'] = $second;
        }
        return $first;
    }

    /**
     * @description:角色授权
     * @author: hkw <hkw925@qq.com>
     * @param $role_id
     * @param array $permission_id
     */
    public function attachPermission($role_id,array $permission_id){
        $role = Role::find($role_id);
        $role->perms()->sync($permission_id);
    }

    public function rolePermission($role_id){
        $permission_id = DB::table('permission_role')->where(['role_id'=>$role_id])->pluck('permission_id')->toArray();
        return $permission_id?:[];
    }

    /**
     * @description:员工列表
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     * @return mixed
     */
    public function userList(array $input)
    {
        $user = new User();
        if ($input['name']) {
            $user = $user->where('name', 'like', '%' . $input['name'] . '%');
        }
        if ($input['phone']) {
            $user = $user->where('phone', 'like', '%' . $input['phone'] . '%');
        }
        $user = $user->where('is_super', 1);
        $user = new QueryPager($user);
        return $user->getPage($input, 'id');
    }

    /**
     * @description:员工新增
     * @author: hkw <hkw925@qq.com>
     * @param array $input
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function userAdd(array $input)
    {
        return User::create([
            'name'        => $input['name'],
            'email'       => $input['email'],
            'password'    => bcrypt($input['password']),
            'phone'       => $input['phone'],
            'passport_id' => @$input['passport_id']?:0,
            'is_super'    => 1
        ]);
    }

    public function userInfo($id){
        return User::find($id);
    }

    /**
     * @description:给员工分配角色
     * @author: hkw <hkw925@qq.com>
     * @param User $user
     * @param array $role_id
     */
    public function userAttachRole(User $user, $role_id)
    {
        $role = Role::find($role_id);
        if(!$user->hasRole($role->name)){
            $user->attachRole($role);
        }
    }

    /**
     * @description:员工编辑
     * @author: hkw <hkw925@qq.com>
     * @param $user_id
     * @param array $input
     * @return mixed
     */
    public function userEdit($user_id, array $input)
    {
        $user = User::find($user_id);
        $role = Role::find($input['role_id']);
        $orole = Role::find($user->roles[0]->id);
        $user->name = $input['name'];
        $user->email = $input['email'];
        //$user->password = $input['password'];
        $user->phone = $input['phone'];
        $user->passport_id = $input['passport_id'];
        if(!$user->hasRole($role->name)){
            $user->detachRole($orole);
            $user->attachRole($role);
        }
        $user->save();
        return $user;
    }

    /**
     * @description:删除员工
     * @author: hkw <hkw925@qq.com>
     * @param $user_id
     * @return bool
     */
    public function userDel($user_id)
    {
        if (User::destroy($user_id)) {
            DB::table('role_user')->where(['user_id' => $user_id])->delete();
        }
        return true;
    }
}