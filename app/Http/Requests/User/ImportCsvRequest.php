<?php

namespace App\Http\Requests\User;

use App\Libs\ConfigUtil;
use Illuminate\Foundation\Http\FormRequest;

class ImportCsvRequest extends FormRequest
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
            'csvFile' => 'required|mimes:csv,txt|max:5000',
        ];
    }

    public function messages() {
        return [
            'csvFile.required' => ConfigUtil::getMessage('E001', 'File'),
            'csvFile.file' => ConfigUtil::getMessage('E007', 'csv'),
            'csvFile.mimes' => ConfigUtil::getMessage('E007', 'csv'),
            'csvFile.max' => ConfigUtil::getMessage('E006', '5'),
        ];
    }
}
