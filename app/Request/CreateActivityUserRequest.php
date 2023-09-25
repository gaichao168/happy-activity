<?php

declare(strict_types=1);

namespace App\Request;

use App\Model\User;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class CreateActivityUserRequest extends FormRequest
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
            'activity_id' => ['required', 'int', 'exists:activity,id'],
            'company_num' => 'required|string',
            'username' => 'required|string',
        ];
    }
}
