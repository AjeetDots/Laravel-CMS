<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

class NewsletterSubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $newsletterUrl = Str::before(url()->previous(), '#') . '#footer-newsletter';

        throw new HttpResponseException(
            redirect($newsletterUrl)
                ->withErrors($validator)
                ->withInput()
        );
    }
}
