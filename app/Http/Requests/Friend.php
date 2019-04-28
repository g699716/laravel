<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Friend extends FormRequest
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
            'f_name'=>'required',
            'f_url'=>'required|url',
        ];
    }

    public function messages()
    {
        return [
            'f_name.required'=>'网站名称必填',
            'f_url.required'=>'网站网址必填',
            'f_url.url'=>'网址格式不正确',
        ];
    }
}
