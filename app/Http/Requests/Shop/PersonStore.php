<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class PersonStore extends FormRequest
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
            'imageurl' => 'required',
            'mname' => 'required|max:6',
            'title'=>'required|max:6',
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
            'mname.required' => '人物名称必须填',
            'mname.max' => '人物名称最大6字符',
            'title.required' => 'title必须填',
            'title.max' => 'title最大6字符',
            'imageurl.required' => '请上传头像',
            'sort.required'=>'排序必须填'

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
