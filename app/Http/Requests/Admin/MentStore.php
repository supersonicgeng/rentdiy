<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MentStore extends FormRequest
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
            'title' => 'required',
            'content' => 'required',

        ];
    }


    /***
     * @return array
     * 验证错误提示信息
     */
    public function messages()
    {
        return [
            'title.required' => '标题必须填写',
            'content.required' => '内容必须填写',
        ];
    }

    // woo 改变验证后的默认行为： 变成 ajax  返回错误信息
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        exit(json_encode(array(
            'status' => 0,
            'msg' => $validator->getMessageBag()->first()
        )));
    }
}
