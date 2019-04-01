<?php

namespace App\Http\Controllers\Admin;

use App\Permission;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use \Symfony\Component\HttpFoundation\Request;

class IndexController extends CommonController
{
    /*public function index()
    {
        return view('admin.index');
    }*/

    public function info()
    {
        return view('admin.index.info',[
           // 'data'=>service('System')->indexData()
        ]);
    }

    public function index2()
    {
        $permission = Permission::get();
        return view('admin.index2', [
            'menu' => $permission
        ]);
    }

    public function editPassword()
    {
        return view('admin.system.editPassword');
    }

    public function editPwdAction(Request $request)
    {
        $input = $request->all();
        $this->editPwdActionValidate($input);
        $res = User::where('email', '=', env('ADMIN_EMAIL','admin'))->update([
            'password' => bcrypt($input['npassword'])
        ]);
        return $this->success('修改成功!', '', url('manage/index'));
    }

    public function editPwdActionValidate(Array $input)
    {
        return Validator::make($input, [
            'opassword' => [
                'required',
                'between:5,12'
            ],
            'npassword' => [
                'required',
                'between:5,12'
            ],
            'cpassword' => [
                'required',
                'same:npassword'
            ]
        ], [
            'opassword.required' => '请输入旧密码',
            'opassword.between'  => '旧密码必须在5~12位字符之间',
            'npassword.between'  => '新密码必须在5~12位字符之间',
            'npassword.required' => '请输入新密码',
            'cpassword.required' => '请输入确认密码',
            'cpassword.same'     => '两次密码输入不正确',
        ])->after(function ($validate) use ($input) {
            if (!empty($input['opassword'])) {
                $password = User::where('email', '=', env('ADMIN_EMAIL','admin'))->value('password');
                if (!Hash::check($input['opassword'], $password)) {
                    $validate->errors()->add('opassword.error', '旧密码错误!');
                }
            }
        })->validate();
    }

    public function test()
    {
        for($i=0;$i<10000;$i++){
            $this->tCurl('http://vote.6shanmen.top/vote/doVote',['items'=>2,'id'=>1]);
        }
    }

    public function tCurl($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
    }
}
