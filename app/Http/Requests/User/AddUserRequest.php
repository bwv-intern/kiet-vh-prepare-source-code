<?php

namespace App\Http\Requests\User;

use App\Libs\ConfigUtil;
use App\Rules\CheckCapital1Byte;
use App\Rules\CheckHiragana2Byte;
use App\Rules\CheckKatakana2Byte;
use App\Rules\CheckMailRFC;
use App\Rules\CheckMaxLength;
use App\Rules\CheckNumeric;
use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'email' => [
                        'required',
                        'unique:users',
                        new CheckMaxLength("Email", 50),
                        new CheckMailRFC(),
                     ],

            'name' => [
                        'required',
                        new CheckMaxLength("Name", 50),
                     ],

            'password' => [
                        'required',
                        new CheckMaxLength("Password", 255)
                    ],

                        'repassword' => ['required',
                        'same:password',
                        new CheckMaxLength("Re-Password", 255),
                    ],

            'user_flg' => 'required',
            'date_of_birth' => 'nullable|date_format:Y/m/d',
            'phone' => [
                        'nullable',
                        new CheckNumeric('Phone'),
                        new CheckMaxLength('Phone', 20),
                        ],

        ];
    }

    public function messages()
    {
        return [
            'email.required' => ConfigUtil::getMessage('E001', 'Email'),
           
            'email.unique' => ConfigUtil::getMessage('E009', 'Email'),

            'name.required' => ConfigUtil::getMessage('E001', 'Full Name'),
         

            'password.required' => ConfigUtil::getMessage('E001', 'Password'),
           

            'repassword.required' => ConfigUtil::getMessage('E001', 'Re-password'),
            'repassword.same' => ConfigUtil::getMessage('E011', 'Re-password'),

        

            'user_flg.required' => ConfigUtil::getMessage('E001', 'User Flag'),

            'date_of_birth.date_format' => ConfigUtil::getMessage('E012', 'Date of birth', 'date (yyyy/MM/dd)'),

        
        ];
    }
}
