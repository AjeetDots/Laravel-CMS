<?php

namespace App\Http\Requests\Frontend;

use App\Support\PhoneDigits;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('phone')) {
            $this->merge([
                'phone' => PhoneDigits::normalizeCombined($this->input('phone')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '_form_context' => 'nullable|string|in:home,contact',
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|max:150',
            'phone'         => PhoneDigits::combinedRules(),
            'subject'       => 'nullable|string|max:200',
            'message'       => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid phone number using digits only.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => $validator->errors()->first() ?: 'The given data was invalid.',
                    'errors' => $validator->errors(),
                ], 422)
            );
        }

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
