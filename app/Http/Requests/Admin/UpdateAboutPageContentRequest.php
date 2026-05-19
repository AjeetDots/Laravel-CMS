<?php

namespace App\Http\Requests\Admin;

use App\Models\AboutPageContent;
use App\Support\ImageUploadRules;
use App\Support\ThemeContentPageTabs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAboutPageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $about = AboutPageContent::listingDataWithDefaults();

        return [
            'about_page_active_section' => ['nullable', 'string', Rule::in(ThemeContentPageTabs::ABOUT)],

            'intro_eyebrow' => 'nullable|string|max:120',
            'intro_title' => 'nullable|string|max:500',
            'story_heading' => 'nullable|string|max:255',
            'story_body_1' => 'nullable|string|max:3000',
            'story_body_2' => 'nullable|string|max:3000',
            'story_body_3' => 'nullable|string|max:3000',
            'image_main_alt' => 'nullable|string|max:255',
            'image_accent_alt' => 'nullable|string|max:255',
            'image_studio_alt' => 'nullable|string|max:255',
            'stat1_num' => 'nullable|string|max:40',
            'stat1_label' => 'nullable|string|max:120',
            'stat2_num' => 'nullable|string|max:40',
            'stat2_label' => 'nullable|string|max:120',
            'stat3_num' => 'nullable|string|max:40',
            'stat3_label' => 'nullable|string|max:120',
            'workshop_eyebrow' => 'nullable|string|max:120',
            'workshop_heading' => 'nullable|string|max:255',
            'workshop_body' => 'nullable|string|max:2000',
            'workshop_btn_text' => 'nullable|string|max:120',
            'workshop_btn_url' => 'nullable|string|max:1000',

            'about_image_main' => ImageUploadRules::requiredUnlessStored(
                5120,
                $about['image_main'] ?? null,
                'remove_about_image_main'
            ),
            'about_image_accent' => ImageUploadRules::requiredUnlessStored(
                5120,
                $about['image_accent'] ?? null,
                'remove_about_image_accent'
            ),
            'about_image_studio' => ImageUploadRules::requiredUnlessStored(
                5120,
                $about['image_studio'] ?? null,
                'remove_about_image_studio'
            ),
            'remove_about_image_main' => 'boolean',
            'remove_about_image_accent' => 'boolean',
            'remove_about_image_studio' => 'boolean',
        ];
    }
}
