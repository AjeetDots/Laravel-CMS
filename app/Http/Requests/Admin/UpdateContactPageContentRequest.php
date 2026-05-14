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

    public function rules(): array
    {
        return [
            'contact_page_active_section' => ['nullable', 'string', Rule::in(ThemeContentPageTabs::CONTACT)],

            'page_title' => 'nullable|string|max:120',
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
            'fallback_phone_display' => 'nullable|string|max:80',
            'fallback_whatsapp_label' => 'nullable|string|max:80',
            'form_title' => 'nullable|string|max:120',
            'form_error_intro' => 'nullable|string|max:500',
            'name_placeholder' => 'nullable|string|max:120',
            'email_placeholder' => 'nullable|string|max:120',
            'phone_field_label' => 'nullable|string|max:120',
            'national_placeholder' => 'nullable|string|max:120',
            'message_placeholder' => 'nullable|string|max:255',
            'submit_label' => 'nullable|string|max:120',
            'show_map' => 'nullable|boolean',
            'map_embed_url' => 'nullable|string|max:5000',

            'contact_hero_bg_image' => ImageUploadRules::nullable(5120),
            'remove_contact_hero_bg_image' => 'boolean',
        ];
    }
}
