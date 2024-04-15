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
            'email' => ['nullable', new CheckMailRFC()],
            'name' => 'nullable',
            'user_flag' => 'nullable|array',
            'user_flag.*' => 'nullable|integer|in:0,1,2',
            'date_of_birth' => ['nullable', 'date_format:Y/m/d'],
            'phone' => 'nullable|numeric',
        ];
    }

    public function messages() {
        return [
 
            'date_of_birth.date_format' => ConfigUtil::getMessage('E012', 'Date of birth', 'date (yyyy/MM/dd)'),
            'phone.numeric' => ConfigUtil::getMessage('E012', 'Phone', 'number'),
            'user_flag.array' => ConfigUtil::getMessage('E012', 'User flag', 'number'),
            'user_flag.*.integer' => ConfigUtil::getMessage('E012', 'User flag', 'number'),
            'user_flag.*.in' => ConfigUtil::getMessage('E012', 'User flag', 'number'),
        ];
    }
}
