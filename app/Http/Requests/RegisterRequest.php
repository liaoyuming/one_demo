<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterRequest extends Request
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
            'username'  => 'required',
            'mobile'    => 'required|min:10',
            'password'  => 'required|min:6',
            'img_captcha' => 'required|size:6',
            'sms_captcha'   => 'required|size:6',
        ];
    }
}
