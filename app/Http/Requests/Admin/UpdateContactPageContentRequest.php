<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use App\Support\ThemeContentPageTabs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContactPageContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('map_embed_url')) {
            $this->merge([
                'map_embed_url' => trim((string) $this->input('map_embed_url')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'contact_page_active_section' => ['nullable', 'string', Rule::in(ThemeContentPageTabs::CONTACT)],

            'hero_line_1' => 'nullable|string|max:255',
            'hero_line_2' => 'nullable|string|max:255',
            'hero_cta' => 'nullable|string|max:120',
            'info_eyebrow' => 'nullable|string|max:120',
            'info_heading_1' => 'nullable|string|max:255',
            'info_heading_2' => 'nullable|string|max:255',
            'info_lead' => 'nullable|string|max:2000',
            'studio_label' => 'nullable|string|max:120',
            'studio_body' => 'nullable|string|max:500',
            'hours_label' => 'nullable|string|max:120',
            'hours_body' => 'nullable|string|max:500',
            'appointment_line' => 'nullable|string|max:255',
            'form_title' => 'nullable|string|max:120',
            'form_error_intro' => 'nullable|string|max:500',
            'name_placeholder' => 'nullable|string|max:120',
            'email_placeholder' => 'nullable|string|max:120',
            'phone_field_label' => 'nullable|string|max:120',
            'national_placeholder' => 'nullable|string|max:120',
            'message_placeholder' => 'nullable|string|max:255',
            'submit_label' => 'nullable|string|max:120',
            'show_map' => 'nullable|boolean',
            'map_embed_url' => ['required_if:show_map,1', 'string', 'max:5000'],

            'contact_hero_bg_image' => ImageUploadRules::nullable(5120),
            'remove_contact_hero_bg_image' => 'boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'map_embed_url.required_if' => 'When the map section is shown, paste a Google Maps embed URL (or turn the switch off).',
        ];
    }
}
