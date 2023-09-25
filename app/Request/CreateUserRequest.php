<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class CreateUserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'company_num'=> 'required|unique:users,company_num',
            'username'=> 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'company_num.required' => '公司統編必填',
            'company_num.unique' => '请勿重复添加',
            'username.required' => '使用者名稱必填',
        ];
    }
}
