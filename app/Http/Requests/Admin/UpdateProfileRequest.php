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
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email,' . $userId,
            'avatar'        => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ];

        if ($this->filled('password')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        } else {
            $rules['current_password'] = ['nullable', 'string'];
        }

        return $rules;
    }
}
