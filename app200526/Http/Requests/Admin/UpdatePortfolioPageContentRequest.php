<?php

namespace App\Http\Requests\Admin;

use App\Support\ThemeContentPageTabs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePortfolioPageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'portfolio_page_active_section' => ['nullable', 'string', Rule::in(ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM)],

            'intro_eyebrow' => 'nullable|string|max:120',
            'intro_title' => 'nullable|string|max:255',
            'intro_body' => 'nullable|string|max:2000',
            'breadcrumb_current' => 'nullable|string|max:120',
            'filter_all_label' => 'nullable|string|max:80',
            'card_link_text' => 'nullable|string|max:120',
            'label_real_project' => 'nullable|string|max:80',
            'label_reference' => 'nullable|string|max:80',
            'empty_message' => 'nullable|string|max:500',
            'empty_btn_text' => 'nullable|string|max:120',
            'empty_btn_url' => 'nullable|string|max:1000',
            'bottom_heading' => 'nullable|string|max:255',
            'bottom_body' => 'nullable|string|max:1000',
            'bottom_btn_text' => 'nullable|string|max:120',
            'bottom_btn_url' => 'nullable|string|max:1000',
        ];
    }
}
