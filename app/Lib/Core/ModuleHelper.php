<?php
namespace App\Lib\Core;

use App\Lib\Auth\UserProfileHelper;
use App\BaseDictionary;
use App\Route;

class ModuleHelper
{
    private $userProfileHelper = null;

    public function __construct(UserProfileHelper $userProfileHelper)
    {
        $this->userProfileHelper = $userProfileHelper;
    }

    //设置被选中的模块/菜单，从而在界面上高亮显示表示当前所在菜单
    public function setActiveModuleFlag($httpMethod, $routeUri)
    {
        $sysMethod = $this->translateToSysHttpMethodCode($httpMethod);

        $menuAndModuleResults = $this->getAllModules();
        $menuAndModules = &$menuAndModuleResults['menus'];
        $moduleCount = count($menuAndModules);

        for ($i=0; $i<$moduleCount; $i++) {
            $module = &$menuAndModules[$i];
            $menugroups = &$module['groups'];

            $groupCount = count($menugroups);

            for ($j=0; $j<$groupCount; $j++) {
                $menugroup = &$menugroups[$j];
                $menus = &$menugroup['menus'];

                $menuCount = count($menus);

                for ($k=0; $k<$menuCount; $k++) {
                    $menuItem = &$menus[$k];

                    if ($routeUri === '/home') {
                        if ($menuItem['is_default'] === BaseDictionary::$KEY_YES) {
                            $menuItem['on'] = true;
                            $menugroup['on'] = true;
                            $module['on'] = true;

                            return $menuAndModuleResults;
                        }
                    } else {
                        if (($menuItem['method'] === Route::$ROUTE_METHOD_ANY ||
                            $menuItem['method'] === $sysMethod) &&
                            $menuItem['route'] === $routeUri) {

                            $menuItem['on'] = true;
                            $menugroup['on'] = true;
                            $module['on'] = true;

                            return $menuAndModuleResults;
                        }
                    }
                }
            }
        }

        return $menuAndModuleResults;
    }

    //根据moduleId来设置被选中的模块/菜单，从而在界面上高亮显示表示当前所在菜单
    public function setActiveModuleFlagByNaviModule($moduleId)
    {
        $httpMethod = null;
        $routeUri = null;

        $menuAndModuleResults = $this->getAllModules();
        $menuAndModules = $menuAndModuleResults['menus'];
        $moduleCount = count($menuAndModules);

        for ($i=0; $i<$moduleCount; $i++) {
            $module = $menuAndModules[$i];
            if ($module['id'] == $moduleId) {
                $httpMethod = $module['route'];
                $routeUri = $module['method'];
            }
        }

        $menuAndModuleResults = $this->setActiveModuleFlag($httpMethod, $routeUri);
        $menuAndModules = &$menuAndModuleResults['menus'];

        foreach ($menuAndModules as &$moduleNavi) {
            if ($moduleNavi['id'] == $moduleId) {
                $moduleNavi['on'] = true;
                break;
            }
        }

        return $menuAndModuleResults;
    }

    private function translateToSysHttpMethodCode($httpMethod)
    {
        $sysMethod = Route::$ROUTE_METHOD_ANY;

        if ($httpMethod === 'GET') {
            $sysMethod = Route::$ROUTE_METHOD_GET;
        } else if ($httpMethod === 'POST') {
            $sysMethod = Route::$ROUTE_METHOD_POST;
        } else if ($httpMethod === 'PUT') {
            $sysMethod = Route::$ROUTE_METHOD_PUT;
        } else if ($httpMethod === 'DELETE') {
            $sysMethod = Route::$ROUTE_METHOD_DELETE;
        } else if ($httpMethod === 'PATCH') {
            $sysMethod = Route::$ROUTE_METHOD_PATCH;
        } else if ($httpMethod === 'OPTIONS') {
            $sysMethod = Route::$ROUTE_METHOD_OPTIONS;
        }

        return $sysMethod;
    }

    private function getAllModules()
    {
        $userGroups = $this->userProfileHelper->userPermissions();

        if (!$this->userProfileHelper->user()->isSuper()) {
            $menuAndModules = null;
            foreach ($userGroups as $userGroup) {
                if ($userGroup['current'] == BaseDictionary::$KEY_YES) {
                    $menuAndModules = [
                        'menus' => $userGroup['menus'],
                        'defaultMenu' => $userGroup['defaultMenu'],
                    ];
                }
            }
        } else {
            $menuAndModules = [
                'menus' => $userGroups[0]['menus'],
                'defaultMenu' => $userGroups[0]['defaultMenu'],
            ];
        }

        return $menuAndModules;
    }
}
