<?php
/**
 * 短信发送模块
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/17 0017
 * Time: 下午 1:40
 */

namespace App\Http\Controllers\Api;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SmsController extends CommonController
{
    public function verifyCode(Request $request){
        $input = $request->all();
        $rule      = [
            'phone'  => [
                'required',
                'regex:/^(1(([3578][0-9])|(47)))\d{8}$/',
            ]
        ];
        $msg       = [
            'phone.required'  => '请输入手机号',
            'phone.regex'     => '手机号格式错误',
        ];
        $validator = Validator::make($input, $rule, $msg);
        if ($validator->fails()) {
            return $this->error(1, $validator->errors()->first());
        } else {
            service('AliSms')->sendCode($input['phone']);
            return $this->success('发送成功');
        }
    }
}