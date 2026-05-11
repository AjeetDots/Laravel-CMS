<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            '_form_context' => 'nullable|string|in:home,contact',
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|max:150',
            'phone'         => 'nullable|string|max:48',
            'subject'       => 'nullable|string|max:200',
            'message'       => 'required|string|min:10',
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
