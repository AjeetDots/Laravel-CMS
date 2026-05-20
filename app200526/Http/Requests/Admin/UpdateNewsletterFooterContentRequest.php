<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsletterFooterContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading' => 'nullable|string|max:120',
            'lead' => 'nullable|string|max:500',
            'email_label' => 'nullable|string|max:120',
            'placeholder' => 'nullable|string|max:120',
            'submit_label' => 'nullable|string|max:80',
            'submit_busy_label' => 'nullable|string|max:40',
            'privacy_text' => 'nullable|string|max:255',
            'message_success' => 'nullable|string|max:255',
            'message_already_subscribed' => 'nullable|string|max:255',
            'message_error_generic' => 'nullable|string|max:255',
            'message_error_network' => 'nullable|string|max:255',
        ];
    }
}
