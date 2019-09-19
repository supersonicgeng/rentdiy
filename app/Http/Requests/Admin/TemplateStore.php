<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TemplateStore extends FormRequest
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
            'image' => 'required',
            'url' => 'required',
            'sort'=>'required'
        ];
    }


    /***
     * @return array
     * 验证错误提示信息
     */
    public function messages()
    {
        return [
            'image.required' => '请上传模板图片',
            'url.required' => '请填写url地址',
            'sort.required' => '请输入排序',
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
