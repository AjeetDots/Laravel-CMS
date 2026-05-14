<?php

namespace App\Http\Requests\Admin;

use App\Support\HomePageAdminTabs;
use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHomePageSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'home_active_section' => ['nullable', 'string', Rule::in(HomePageAdminTabs::SECTION_KEYS)],

            'home_page_title' => 'nullable|string|max:120',

            'home_atelier_is_enabled' => 'boolean',
            'home_atelier_kicker' => 'nullable|string|max:120',
            'home_atelier_heading_line_1' => 'nullable|string|max:255',
            'home_atelier_heading_line_2' => 'nullable|string|max:255',
            'home_atelier_heading_line_3' => 'nullable|string|max:255',
            'home_atelier_body' => 'nullable|string|max:2000',
            'home_atelier_cta_text' => 'nullable|string|max:120',
            'home_atelier_cta_url' => 'nullable|string|max:1000',
            'home_atelier_booking_label' => 'nullable|string|max:120',
            'home_atelier_booking_url' => 'nullable|string|max:1000',
            'home_atelier_primary_image' => ImageUploadRules::nullable(4096),
            'home_atelier_secondary_image' => ImageUploadRules::nullable(4096),

            'home_finishes_is_enabled' => 'boolean',
            'home_finishes_eyebrow' => 'nullable|string|max:120',
            'home_finishes_heading_line_1' => 'nullable|string|max:255',
            'home_finishes_heading_line_2' => 'nullable|string|max:255',
            'home_finishes_card_label' => 'nullable|string|max:80',
            'home_finishes_button_text' => 'nullable|string|max:120',
            'home_finishes_button_url' => 'nullable|string|max:1000',

            'home_services_is_enabled' => 'boolean',
            'home_services_eyebrow' => 'nullable|string|max:120',
            'home_services_heading_line_1' => 'nullable|string|max:255',
            'home_services_heading_line_2' => 'nullable|string|max:255',
            'home_services_button_text' => 'nullable|string|max:120',
            'home_services_button_url' => 'nullable|string|max:1000',
            'home_services_card_link_text' => 'nullable|string|max:80',

            'home_why_is_enabled' => 'boolean',
            'home_why_eyebrow' => 'nullable|string|max:120',
            'home_why_heading' => 'nullable|string|max:255',
            'home_why_lead' => 'nullable|string|max:1000',

            'home_why_card_1_icon' => 'nullable|string|max:80',
            'home_why_card_1_title' => 'nullable|string|max:120',
            'home_why_card_1_desc' => 'nullable|string|max:300',
            'home_why_card_2_icon' => 'nullable|string|max:80',
            'home_why_card_2_title' => 'nullable|string|max:120',
            'home_why_card_2_desc' => 'nullable|string|max:300',
            'home_why_card_3_icon' => 'nullable|string|max:80',
            'home_why_card_3_title' => 'nullable|string|max:120',
            'home_why_card_3_desc' => 'nullable|string|max:300',
            'home_why_card_4_icon' => 'nullable|string|max:80',
            'home_why_card_4_title' => 'nullable|string|max:120',
            'home_why_card_4_desc' => 'nullable|string|max:300',

            'home_process_is_enabled' => 'boolean',
            'home_process_eyebrow' => 'nullable|string|max:120',
            'home_process_heading_line_1' => 'nullable|string|max:255',
            'home_process_heading_line_2' => 'nullable|string|max:255',
            'home_process_step_1_num' => 'nullable|string|max:10',
            'home_process_step_1_title' => 'nullable|string|max:120',
            'home_process_step_1_desc' => 'nullable|string|max:300',
            'home_process_step_2_num' => 'nullable|string|max:10',
            'home_process_step_2_title' => 'nullable|string|max:120',
            'home_process_step_2_desc' => 'nullable|string|max:300',
            'home_process_step_3_num' => 'nullable|string|max:10',
            'home_process_step_3_title' => 'nullable|string|max:120',
            'home_process_step_3_desc' => 'nullable|string|max:300',
            'home_process_step_4_num' => 'nullable|string|max:10',
            'home_process_step_4_title' => 'nullable|string|max:120',
            'home_process_step_4_desc' => 'nullable|string|max:300',

            'home_commissions_is_enabled' => 'boolean',
            'home_commissions_eyebrow' => 'nullable|string|max:120',
            'home_commissions_heading_line_1' => 'nullable|string|max:255',
            'home_commissions_button_text' => 'nullable|string|max:120',
            'home_commissions_button_url' => 'nullable|string|max:1000',

            'home_begin_cta_is_enabled' => 'boolean',
            'home_begin_cta_eyebrow' => 'nullable|string|max:120',
            'home_begin_cta_title_line_1' => 'nullable|string|max:255',
            'home_begin_cta_title_line_2' => 'nullable|string|max:255',
            'home_begin_cta_primary_btn_text' => 'nullable|string|max:120',
            'home_begin_cta_primary_btn_url' => 'nullable|string|max:1000',
            'home_begin_cta_secondary_btn_text' => 'nullable|string|max:120',
            'home_begin_cta_secondary_btn_url' => 'nullable|string|max:1000',
            'home_begin_cta_bg_image' => ImageUploadRules::nullable(4096),

            'home_contact_band_is_enabled' => 'boolean',
            'home_contact_band_eyebrow' => 'nullable|string|max:120',
            'home_contact_band_heading' => 'nullable|string|max:255',
            'home_contact_band_panel_title' => 'nullable|string|max:120',
            'home_contact_band_name_placeholder' => 'nullable|string|max:120',
            'home_contact_band_email_placeholder' => 'nullable|string|max:120',
            'home_contact_band_phone_placeholder' => 'nullable|string|max:120',
            'home_contact_band_message_placeholder' => 'nullable|string|max:255',
            'home_contact_band_submit_text' => 'nullable|string|max:120',
            'home_contact_band_visual_image' => ImageUploadRules::nullable(4096),

            'home_brands_strip_is_enabled' => 'boolean',
            'home_brands_strip_kicker' => 'nullable|string|max:120',
            'home_brands_strip_title_line_1' => 'nullable|string|max:255',
            'home_brands_strip_title_line_2' => 'nullable|string|max:255',
            'home_brands_strip_marquee_segments' => 'nullable|integer|min:1|max:20',

            'home_blog_preview_is_enabled' => 'boolean',
            'home_blog_preview_eyebrow' => 'nullable|string|max:120',
            'home_blog_preview_heading' => 'nullable|string|max:255',
            'home_blog_preview_button_text' => 'nullable|string|max:120',
            'home_blog_preview_button_url' => 'nullable|string|max:1000',
            'home_blog_preview_read_more_text' => 'nullable|string|max:80',
        ];
    }
}
