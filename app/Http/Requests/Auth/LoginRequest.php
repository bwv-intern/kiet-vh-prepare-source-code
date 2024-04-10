<?php

namespace App\Http\Requests\Auth;

use App\Libs\ConfigUtil;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckMailRFC;
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'email' => [
                'required',
                new CheckMailRFC(),
            ],
            'password' => [
                'required',
            ],
        ];
    }

    /**
     * Validation error message
     *
     * @return array
     */
    public function messages() {
        return [
            'email.required' => ConfigUtil::getMessage('E001', 'Email'),
            'password.required' => ConfigUtil::getMessage('E001', 'Password'),
        ];
    }
}
