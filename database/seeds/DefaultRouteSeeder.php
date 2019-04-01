<?php

use Illuminate\Database\Seeder;
use App\Permission;

class DefaultRouteSeeder extends Seeder
{
    private $permission;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->permission = new Permission();
        $this->passports();
        $this->systemMange();
        //$this->groupManage();
        //$this->weChatemanage();
        Service('Route')->updateRouteCache();
        Service('Seed')->updateAdminPermission();
    }

    private function passports()
    {
        $passports     = $this->permission->create([
            'name'         => 'passports',
            'pid'          => Permission::$TOP_CATE_PID,
            'sort'         => 0,
            'module'       => 'Admin',
            'method'       => null,
            'icon'         => 'fa fa-user',
            'display_name' => '用户管理',
            'description'  => '用户管理',
        ]);
        $passport_list = $this->permission->create([
            'name'         => 'manage/passportList',
            'pid'          => $passports->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'MemberController@userList',
            'display_name' => '会员列表'
        ]);
        $game_list = $this->permission->create([
            'name'         => 'manage/gameList',
            'pid'          => $passports->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'MemberController@gameList',
            'display_name' => '游戏排行'
        ]);
        $this->permission->create([
            'name'         => 'manage/member/userDetail/{passport_id}',
            'pid'          => $passport_list->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'MemberController@userInfo',
            'display_name' => '编辑用户信息'
        ]);
        $this->permission->create([
            'name'         => 'manage/member/gameInfo/{passport_id}',
            'pid'          => $passport_list->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'MemberController@gameInfo',
            'display_name' => '库存管理'
        ]);
        /*$qrcode_set = $this->permission->create([
            'name'         => 'manage/member/qrCode',
            'pid'          => $passports->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'MemberController@qrCode',
            'display_name' => '客服设置'
        ]);*/
    }


    private function weChatemanage()
    {
        $weChatMangage = $this->permission->create([
            'name'         => 'wechat',
            'pid'          => Permission::$TOP_CATE_PID,
            'sort'         => 0,
            'icon'         => 'fa fa-weixin',
            'module'       => 'Admin',
            'display_name' => '公众号管理'
        ]);

        /*$weChatConfig = $this->permission->create([
            'name'         => 'manage/wxConfig',
            'pid'          => $weChatMangage->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@wxConfig',
            'display_name' => '公众号配置'
        ]);*/

        $weChatCustom = $this->permission->create([
            'name'         => 'manage/wxMenu',
            'pid'          => $weChatMangage->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_GET,
            'module'       => 'Admin',
            'method'       => 'SystemController@wxMenu',
            'display_name' => '自定义菜单'
        ]);

        $weChatUpdateCustom = $this->permission->create([
            'name'         => 'manage/updateWxMenu',
            'pid'          => $weChatCustom->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@updateWxMenu',
            'display_name' => '更新自定义菜单'
        ]);

        $weChatDelCustom = $this->permission->create([
            'name'         => 'manage/delWxMenu/{key1}/{key2}',
            'pid'          => $weChatCustom->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_GET,
            'module'       => 'Admin',
            'method'       => 'SystemController@delWxMenu',
            'display_name' => '删除自定义菜单'
        ]);

        $weChatRefreshCustom = $this->permission->create([
            'name'         => 'manage/refreshWxMenu',
            'pid'          => $weChatCustom->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_GET,
            'module'       => 'Admin',
            'method'       => 'SystemController@refreshWxMenu',
            'display_name' => '刷新自定义菜单'
        ]);

        $weChatEditCustom = $this->permission->create([
            'name'         => 'manage/editWxMenu/{key1}/{key2}',
            'pid'          => $weChatCustom->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@editWxMenu',
            'display_name' => '编辑自定义菜单'
        ]);
    }

    private function systemMange()
    {
        $sysManage = $this->permission->create([
            'name'         => 'system',
            'pid'          => Permission::$TOP_CATE_PID,
            'sort'         => 0,
            'module'       => 'Admin',
            'icon'         => 'fa fa-windows',
            'display_name' => '系统管理'
        ]);

        /*$sysSet = $this->permission->create([
            'name'         => 'manage/shopSet',
            'pid'          => $sysManage->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@shopSet',
            'display_name' => '系统设置'
        ]);*/
        /*$sysSet = $this->permission->create([
            'name'         => 'manage/paySet',
            'pid'          => $sysManage->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@paySet',
            'display_name' => '支付设置'
        ]);*/
        /*$sysSet = $this->permission->create([
            'name'         => 'manage/announcement',
            'pid'          => $sysManage->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@announcement',
            'display_name' => '系统公告'
        ]);*/
        $gameList       = $this->permission->create([
            'name'         => 'manage/gameConfig',
            'pid'          => $sysManage->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@gameConfig',
            'display_name' => '游戏设置'
        ]);
        $shareList       = $this->permission->create([
            'name'         => 'manage/shareConfig',
            'pid'          => $sysManage->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'SystemController@shareConfig',
            'display_name' => '分享设置'
        ]);
    }

    private function groupManage()
    {
        $groups = $this->permission->create([
            'name'         => 'groups',
            'pid'          => Permission::$TOP_CATE_PID,
            'sort'         => 0,
            'module'       => 'Admin',
            'icon'         => 'fa fa-group',
            'display_name' => '权限管理'
        ]);
        $roles  = $this->permission->create([
            'name'         => 'manage/roles',
            'pid'          => $groups->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'PermissionController@roles',
            'display_name' => '角色列表'
        ]);
        $this->permission->create([
            'name'         => 'manage/rolesPermission/{id}',
            'pid'          => $roles->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'PermissionController@rolesPermission',
            'display_name' => '角色授权'
        ]);
        $users = $this->permission->create([
            'name'         => 'manage/users',
            'pid'          => $groups->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'PermissionController@users',
            'display_name' => '管理员列表'
        ]);
        $this->permission->create([
            'name'         => 'manage/usersAdd',
            'pid'          => $users->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'PermissionController@usersAdd',
            'display_name' => '管理员新增'
        ]);
        $this->permission->create([
            'name'         => 'manage/usersEdit/{id}',
            'pid'          => $users->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'PermissionController@usersEdit',
            'display_name' => '管理员编辑'
        ]);
        $this->permission->create([
            'name'         => 'manage/usersDel/{id}',
            'pid'          => $users->id,
            'sort'         => 0,
            'type'         => Permission::$TYPE_ANY,
            'module'       => 'Admin',
            'method'       => 'PermissionController@usersDel',
            'display_name' => '管理员删除'
        ]);
    }

}
