<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\_\-]+/|unique:users,name,' . Auth::id(),
            'email' => 'required|email',
            'introduction' => 'max:80',
            'avator' => 'mimes:jpeg,jpg,gif,png|dimensions:min_width=208,min_height=208',
        ];
    }

    public function messages()
    {
        return [
            'avator.dimensions' => '头像清晰度不够，需要宽和高208px以上',
            'avator.mimes' => '头像必须是jpeg,jpg,gif,png格式',
            'name.unique' => '用户名已存在',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线',
            'name.required' => '请输入用户名',
            'name.between' => '用户名必须介于3~25位之间'
        ];
    }
}
