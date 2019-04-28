<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'u_email'=>'required|email',
            'email_code'=>'required|digits:6',
            'pwd'=>'required|alpha_dash|digits_between:2,12',
            'repwd'=>'required|same:pwd',
        ];
    }

    public function messages()
    {
        return [
            'u_email.required'=>'邮箱必填',
            'u_email.email'=>'邮箱格式不正确',
            'email_code.required'=>'验证码必填',
            'email_code.digits'=>'验证码格式不正确',
            'pwd.required'=>'密码必填',
            'pwd.alpha_dash'=>'密码格式不正确',
            'pwd.digits_between'=>'密码的长度为2~12位',
            'repwd.required'=>'确认密码必填',
            'repwd.same'=>'两次密码不一致',
        ];
    }
}
