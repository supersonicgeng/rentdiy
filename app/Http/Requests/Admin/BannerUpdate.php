<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdate extends FormRequest
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
            'image' => 'required',


        ];
    }


    /***
     * @return array
     * 验证错误提示信息
     */
    public function messages()
    {
        return [
            'title' => '标题必须填',
            'image' => '封面图必须上传',

        ];
    }
}
