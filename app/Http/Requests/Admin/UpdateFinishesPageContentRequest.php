<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinishesPageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'intro_eyebrow' => 'nullable|string|max:120',
            'intro_title' => 'nullable|string|max:255',
            'intro_body' => 'nullable|string|max:2000',
            'card_label_fallback' => 'nullable|string|max:120',
            'empty_message' => 'nullable|string|max:500',
            'empty_btn_text' => 'nullable|string|max:120',
            'empty_btn_url' => 'nullable|string|max:1000',
            'bottom_eyebrow' => 'nullable|string|max:120',
            'bottom_heading' => 'nullable|string|max:255',
            'bottom_body' => 'nullable|string|max:1000',
            'bottom_btn_text' => 'nullable|string|max:120',
            'bottom_btn_url' => 'nullable|string|max:1000',
        ];
    }
}
