<?php

namespace App\Http\Requests\User;

use App\Libs\ConfigUtil;
use App\Rules\CheckMailRFC;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'email' => ['nullable','max:50', new CheckMailRFC()],
            'name' => 'nullable|max:50',
            'user_flag' => 'nullable|array',
            'user_flag.*' => 'nullable|integer|between:0,2',
            'date_of_birth' => ['nullable', 'date_format:d/m/Y'],
            'phone' => 'nullable|numeric|max:20'
        ];
    }

    public function messages() {
        return [
            'email.max' => ConfigUtil::getMessage('E002', 'Name', '50', mb_strlen($this)),
 
            'date_of_birth.date_format' => ConfigUtil::getMessage('E012', 'Date of birth', 'date (dd/MM/yyyy)'),
        
            'name.max' => ConfigUtil::getMessage('E002', 'Name', '50', mb_strlen($this)),

            'phone.numeric' => ConfigUtil::getMessage('E012', 'Phone', 'number'),
            'phone.max' => ConfigUtil::getMessage('E002', 'Phone', '20', mb_strlen($this)),

            'user_flag.array' => ConfigUtil::getMessage('E012', 'Date of birth', 'number'),
            'user_flag.*.integer' => ConfigUtil::getMessage('E012', 'User flag', 'number'),
            'user_flag.*.between' => ConfigUtil::getMessage('E012', 'User flag', 'number'),
        ];
    }
}
