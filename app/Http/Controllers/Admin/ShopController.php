<?php

namespace App\Http\Controllers\Admin;

use App\Model\ShopUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShopController extends CommonController
{
    public function shopUserList(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $list  = service('Shop')->shopList($input);
            return view('admin.shop.user.userAjax', ['list' => $list['list']]);
        } else {
            return view('admin.shop.user.user');
        }
    }

    public function shopUserEdit(Request $request)
    {
        $id       = $request->id;
        $userInfo = ShopUsers::where('id', $id)->first();
        return view('admin.shop.user.edit', [
            'id'        => $id,
            'user_info' => $userInfo
        ]);
    }

    public function shopUserEditAction(Request $request)
    {
        $input = $request->all();
        $this->shopUserEditActionValidate($input);
        $shopService = Service('Shop');
        $shopService->shopUserEditAction($input);
        return $this->success('修改成功!', [], url('manage/shopUser'));
    }

    protected function shopUserEditActionValidate(Array $input)
    {
        return Validator::make($input, [
            'name'     => 'required|string|between:2,20',
            'account'  => [
                'required',
                'integer',
                'regex:/^(1(([35][0-9])|(47)|[8][0126789]))\d{8}$/',
                'unique:shop_users,account,' . $input['id']
            ],
        ], [
            'name.required'    => '请输入名称',
            'name.between'     => '名称长度必须在2~20个字符之间',
            'account.required' => '请输入商店名称',
            'account.integer'  => '手机号不正确',
            'account.regex'    => '手机号不正确',
            'account.unique'   => '手机号已被注册'
        ])->after(function ($validator) use ($input) {
            if (!empty($input['password'])) {
                $pattern = '/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,18}$/';
                if (!preg_match($pattern, $input['password'])) {
                    $validator->errors()->add('password.error', '请输入6~18位的字母与数字的组合!');
                }
            }
        })->validate();
    }
}
