<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users,username,' . $this->route('user') . '|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('user') . '|max:255',
            'old_password' => 'required',
            'password' => 'min:6|confirmed|max:255',
        ];
    }


    /***
     * @return array
     * 验证错误提示信息
     */
    public function messages()
    {
        return [
            'old_password.required' => '原始密码未填',
            'password.required' => '新密码未填写',
        ];
    }
}
