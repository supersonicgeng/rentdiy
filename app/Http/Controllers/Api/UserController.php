<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends CommonController
{
    /**
     * @description:用户注册
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    Public function userRegister(Request $request)
    {
        return service('User')->Register($request->all());
    }

    /**
     * @description:用户登陆
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function userLogin(Request $request)
    {
        return service('User')->Login($request->all());
    }

    /**
     * @description:修改密码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        return service('User')->changePassword($request->all());
    }


    /**
     * @description:忘记密码
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        return service('User')->forgetPassword($request->all());
    }


    /**
     * @description:用户获得各角色下的角色id
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRoleId(Request $request)
    {
        return service('User')->getUserRoleId($request->all());
    }
    /**
     * @description:成为房东
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function becomeLandlord(Request $request)
    {
        return service('User')->becomeLandlord($request->all());
    }
    /**
     * @description:成为服务商
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function becomeProviders(Request $request)
    {
        return service('User')->becomeProviders($request->all());
    }
    /**
     * @description:成为租客
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function becomeTenement(Request $request)
    {
        return service('User')->becomeTenement($request->all());
    }

    /**
     * @description:更新头像
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHeadImg(Request $request)
    {
        return service('User')->updateHeadImg($request->all());
    }

    /**
     * @description:增加手机
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPhone(Request $request)
    {
        return service('User')->addPhone($request->all());
    }

    /**
     * @description:增加邮箱
     * @author: syg <13971394623@163.com>
     * @param $code
     * @param $message
     * @param array|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEmail(Request $request)
    {
        return service('User')->addEmail($request->all());
    }
}
