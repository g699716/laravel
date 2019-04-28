<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsersRequest extends FormRequest
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
            'username'=>'required|unique:users|min:3|max:30',
        ];
    }

    public function messages()
    {
        return [
            'username.required'=>'用户名必填',
            'username.unique'=>'用户名已被占用',
            'username.min'=>'用户名长度最小为3位',
            'username.max'=>'用户名长度最大为30'
        ];
    }
}
