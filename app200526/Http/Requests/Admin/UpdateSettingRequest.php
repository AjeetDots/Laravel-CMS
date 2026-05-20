<?php

namespace App\Http\Requests\Admin;

use App\Support\PhoneDigits;
use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Which Site settings tab contains this request field (for redirect UX).
     *
     * @return 'general'|'notifications'|'social'|'logos'|'smtp'
     */
    public static function settingsTabForField(string $field): string
    {
        $logos = ['site_logo', 'backend_logo', 'site_logo_footer', 'site_favicon'];

        if (in_array($field, $logos, true)) {
            return 'logos';
        }
        if (str_starts_with($field, 'social_')) {
            return 'social';
        }
        if ($field === 'admin_notification_email') {
            return 'notifications';
        }
        if (str_starts_with($field, 'mail_smtp_') || str_starts_with($field, 'mail_from_')) {
            return 'smtp';
        }
        if (in_array($field, ['site_phone_country_id', 'site_phone_national', 'site_whatsapp_country_id', 'site_whatsapp_national'], true)) {
            return 'general';
        }

        return 'general';
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('site_phone_national')) {
            $this->merge(['site_phone_national' => PhoneDigits::sanitizeNational($this->input('site_phone_national'))]);
        }
        $national = (string) $this->input('site_phone_national', '');
        if ($national === '') {
            $this->merge(['site_phone_country_id' => null]);
        } elseif ($this->input('site_phone_country_id') === '' || $this->input('site_phone_country_id') === null) {
            $this->merge(['site_phone_country_id' => null]);
        }

        if ($this->has('site_whatsapp_national')) {
            $this->merge(['site_whatsapp_national' => PhoneDigits::sanitizeNational($this->input('site_whatsapp_national'))]);
        }
        $whatsappNational = (string) $this->input('site_whatsapp_national', '');
        if ($whatsappNational === '') {
            $this->merge(['site_whatsapp_country_id' => null]);
        } elseif ($this->input('site_whatsapp_country_id') === '' || $this->input('site_whatsapp_country_id') === null) {
            $this->merge(['site_whatsapp_country_id' => null]);
        }

        $social = [];
        foreach (['social_facebook', 'social_twitter', 'social_linkedin', 'social_instagram'] as $key) {
            if ($this->has($key) && $this->input($key) === '') {
                $social[$key] = null;
            }
        }
        if ($social !== []) {
            $this->merge($social);
        }

        if ($this->has('mail_smtp_host') && trim((string) $this->input('mail_smtp_host')) === '') {
            $this->merge(['mail_smtp_host' => null]);
        }
        if ($this->has('mail_smtp_port') && ($this->input('mail_smtp_port') === '' || $this->input('mail_smtp_port') === null)) {
            $this->merge(['mail_smtp_port' => null]);
        } elseif ($this->has('mail_smtp_port')) {
            $this->merge(['mail_smtp_port' => (int) $this->input('mail_smtp_port')]);
        }
        if ($this->has('mail_smtp_username') && trim((string) $this->input('mail_smtp_username')) === '') {
            $this->merge(['mail_smtp_username' => null]);
        }
        if ($this->has('mail_from_address') && trim((string) $this->input('mail_from_address')) === '') {
            $this->merge(['mail_from_address' => null]);
        }
        if ($this->has('mail_from_name') && trim((string) $this->input('mail_from_name')) === '') {
            $this->merge(['mail_from_name' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'site_name' => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:200',
            'site_email' => 'nullable|email',
            'admin_notification_email' => 'nullable|email',
            'site_phone_country_id' => ['nullable', 'integer', Rule::exists('phone_countries', 'id')->where('is_active', true)],
            'site_phone_national' => PhoneDigits::nationalRules(),
            'site_whatsapp_country_id' => ['nullable', 'integer', Rule::exists('phone_countries', 'id')->where('is_active', true)],
            'site_whatsapp_national' => PhoneDigits::nationalRules(),
            'site_address' => 'nullable|string|max:500',
            'footer_about' => 'nullable|string|max:1000',
            'copyright_text' => 'nullable|string',
            'page_default_template' => ['required', 'string', Rule::in(array_keys(\App\Models\Page::defaultTemplateOptions()))],
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'site_logo' => ImageUploadRules::requiredUnlessSetting(2048, 'site_logo', 'remove_site_logo'),
            'backend_logo' => ImageUploadRules::requiredUnlessSetting(2048, 'backend_logo', 'remove_backend_logo'),
            'site_logo_footer' => ImageUploadRules::requiredUnlessSetting(2048, 'site_logo_footer', 'remove_site_logo_footer'),
            'site_favicon' => ImageUploadRules::requiredFaviconUnlessSetting('remove_site_favicon'),
            'remove_site_logo' => 'boolean',
            'remove_backend_logo' => 'boolean',
            'remove_site_logo_footer' => 'boolean',
            'remove_site_favicon' => 'boolean',
            'mail_smtp_host' => 'nullable|string|max:255',
            'mail_smtp_port' => 'nullable|integer|min:1|max:65535',
            'mail_smtp_username' => 'nullable|string|max:255',
            'mail_smtp_password' => 'nullable|string|max:255',
            'mail_smtp_encryption' => ['nullable', 'string', Rule::in(['', 'tls', 'ssl'])],
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:150',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $nat = (string) $this->input('site_phone_national', '');
            $cid = $this->input('site_phone_country_id');
            if ($nat !== '' && empty($cid)) {
                $v->errors()->add('site_phone_country_id', 'Select a country / dial code when you enter a phone number.');
            }
            if (! empty($cid) && $nat === '') {
                $v->errors()->add('site_phone_national', 'Enter the rest of the number (digits only), or clear the country.');
            }

            $waNat = (string) $this->input('site_whatsapp_national', '');
            $waCid = $this->input('site_whatsapp_country_id');
            if ($waNat !== '' && empty($waCid)) {
                $v->errors()->add('site_whatsapp_country_id', 'Select a country / dial code when you enter a WhatsApp number.');
            }
            if (! empty($waCid) && $waNat === '') {
                $v->errors()->add('site_whatsapp_national', 'Enter the rest of the WhatsApp number (digits only), or clear the country.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'site_name.required' => 'Site name is required — open the General tab and enter a name.',
            'site_name.max' => 'Site name may not be longer than 100 characters.',
            'site_email.email' => 'Enter a valid email address on the General tab, or leave the field empty.',
            'admin_notification_email.email' => 'Enter a valid admin notification email on the Notifications tab, or leave the field empty.',
            'social_facebook.url' => 'Facebook must be a full URL (https://…), or leave it empty — check the Social tab.',
            'social_twitter.url' => 'Twitter must be a full URL (https://…), or leave it empty — check the Social tab.',
            'social_linkedin.url' => 'LinkedIn must be a full URL (https://…), or leave it empty — check the Social tab.',
            'social_instagram.url' => 'Instagram must be a full URL (https://…), or leave it empty — check the Social tab.',
            'site_logo.max' => 'Header logo file is too large — check the Site logos tab.',
            'backend_logo.max' => 'Backend logo file is too large — check the Site logos tab.',
            'site_logo_footer.max' => 'Footer logo file is too large — check the Site logos tab.',
            'site_favicon.max' => 'Favicon file is too large — check the Site logos tab.',
            'site_phone_country_id.exists' => 'Choose a valid country from the list.',
            'site_phone_national.regex' => 'Phone number must contain digits only (no letters or symbols).',
            'site_whatsapp_national.regex' => 'WhatsApp number must contain digits only (no letters or symbols).',
            'mail_from_address.email' => 'Enter a valid “From” email on the SMTP tab, or leave it empty.',
        ];
    }

    public function attributes(): array
    {
        return [
            'site_name' => 'site name',
            'site_email' => 'site email',
            'admin_notification_email' => 'admin notification email',
            'social_facebook' => 'Facebook URL',
            'social_twitter' => 'Twitter URL',
            'social_linkedin' => 'LinkedIn URL',
            'social_instagram' => 'Instagram URL',
            'site_logo' => 'header logo',
            'backend_logo' => 'backend logo',
            'site_logo_footer' => 'footer logo',
            'site_favicon' => 'favicon',
            'site_phone_country_id' => 'phone country',
            'site_phone_national' => 'phone number',
            'site_whatsapp_country_id' => 'WhatsApp country',
            'site_whatsapp_national' => 'WhatsApp number',
            'mail_smtp_host' => 'SMTP host',
            'mail_smtp_port' => 'SMTP port',
            'mail_smtp_username' => 'SMTP username',
            'mail_smtp_password' => 'SMTP password',
            'mail_smtp_encryption' => 'SMTP encryption',
            'mail_from_address' => 'from email address',
            'mail_from_name' => 'from name',
            'page_default_template' => 'default page template',
        ];
    }
}
