<?php

namespace App\Http\Requests\User;

use App\Libs\ConfigUtil;
use App\Rules\CheckMailRFC;
use App\Rules\CheckMaxLength;
use App\Rules\CheckNumeric;
use Illuminate\Foundation\Http\FormRequest;

class EditUserRequest extends FormRequest
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
        $rules = [
            'email' => [
                        'required',
                        new CheckMailRFC(),
                        new CheckMaxLength("Email", 50),
                        'unique:users,email,' . $this->id,
                       ],

            'name' => [ 'required',
                            new CheckMaxLength("Name", 50),
                        ],

            'password' => $this->isPasswordUpdateRequested() ? ['required'] : '',

            'repassword' => $this->isPasswordUpdateRequested() ? ['required','same:password'] : '',

            'user_flg' => ['required',
                            'in:0,1,2'
                          ],

            'date_of_birth' => 'nullable|date_format:Y/m/d',

            'phone' => ['nullable',
                        new CheckMaxLength('Phone', 20),
                        new CheckNumeric('Phone'),
                        ],
        ];

        return $rules;
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

            'userFlag.required' => ConfigUtil::getMessage('E001', 'User Flag'),
            'userFlag.in' =>  ConfigUtil::getMessage('E012', 'User Flag', 'number'),

            'date_of_birth.date_format' => ConfigUtil::getMessage('E012', 'Date of birth', 'date (dd/MM/yyyy)'),

        ];
    }

    private function isPasswordUpdateRequested(): bool
    {
        return $this->filled('password');
    }
}
