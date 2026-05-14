<?php

namespace App\Http\Requests\Admin;

use App\Support\ThemeContentPageTabs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServicesPageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'services_page_active_section' => ['nullable', 'string', Rule::in(ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM)],

            'page_title' => 'nullable|string|max:120',
            'intro_eyebrow' => 'nullable|string|max:120',
            'intro_title' => 'nullable|string|max:500',
            'intro_body' => 'nullable|string|max:2000',
            'service_cta_prefix' => 'nullable|string|max:120',
            'empty_message' => 'nullable|string|max:500',
            'empty_btn_text' => 'nullable|string|max:120',
            'empty_btn_url' => 'nullable|string|max:1000',
            'bottom_eyebrow' => 'nullable|string|max:120',
            'bottom_heading' => 'nullable|string|max:500',
            'bottom_body' => 'nullable|string|max:1000',
            'bottom_btn_text' => 'nullable|string|max:120',
            'bottom_btn_url' => 'nullable|string|max:1000',
        ];
    }
}
