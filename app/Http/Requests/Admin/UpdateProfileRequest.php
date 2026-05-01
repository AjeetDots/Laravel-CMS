<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = Auth::id();

        $rules = [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $userId,
        ];

        if ($this->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        return $rules;
    }
}
