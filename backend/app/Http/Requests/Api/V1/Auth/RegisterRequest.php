<?php

namespace App\Http\Requests\Api\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.min'        => 'The password must be at least 8 characters.',
            'password.mixed_case' => 'The password must contain both uppercase and lowercase letters.',
            'password.numbers'    => 'The password must contain at least one number.',
            'password.symbols'    => 'The password must contain at least one special character.',
        ];
    }
}
