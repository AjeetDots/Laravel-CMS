<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryPageContentRequest extends FormRequest
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
            'filter_all_label' => 'nullable|string|max:80',
            'grid_category_fallback' => 'nullable|string|max:120',
            'empty_message' => 'nullable|string|max:500',
            'empty_btn_text' => 'nullable|string|max:120',
            'empty_btn_url' => 'nullable|string|max:1000',
            'bottom_heading' => 'nullable|string|max:255',
            'bottom_btn_text' => 'nullable|string|max:120',
            'bottom_btn_url' => 'nullable|string|max:1000',
        ];
    }
}
