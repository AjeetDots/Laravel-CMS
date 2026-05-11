<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Which Site settings tab contains this request field (for redirect UX).
     *
     * @return 'general'|'notifications'|'social'|'logos'
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
        if (in_array($field, ['site_phone_country_id', 'site_phone_national'], true)) {
            return 'general';
        }

        return 'general';
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('site_phone_national')) {
            $this->merge(['site_phone_national' => trim((string) $this->input('site_phone_national'))]);
        }
        $national = trim((string) $this->input('site_phone_national', ''));
        if ($national === '') {
            $this->merge(['site_phone_country_id' => null]);
        } elseif ($this->input('site_phone_country_id') === '' || $this->input('site_phone_country_id') === null) {
            $this->merge(['site_phone_country_id' => null]);
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
    }

    public function rules(): array
    {
        return [
            'site_name' => 'required|string|max:100',
            'site_tagline' => 'nullable|string|max:200',
            'site_email' => 'nullable|email',
            'admin_notification_email' => 'nullable|email',
            'site_phone_country_id' => ['nullable', 'integer', Rule::exists('phone_countries', 'id')->where('is_active', true)],
            'site_phone_national' => 'nullable|string|max:24',
            'site_address' => 'nullable|string|max:500',
            'footer_about' => 'nullable|string|max:1000',
            'copyright_text' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'site_logo' => ImageUploadRules::nullable(2048),
            'backend_logo' => ImageUploadRules::nullable(2048),
            'site_logo_footer' => ImageUploadRules::nullable(2048),
            'site_favicon' => ['nullable', File::types(['ico', 'png', 'svg', 'jpg', 'jpeg', 'gif', 'webp'])->max(512)],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $nat = trim((string) $this->input('site_phone_national', ''));
            $cid = $this->input('site_phone_country_id');
            if ($nat !== '' && empty($cid)) {
                $v->errors()->add('site_phone_country_id', 'Select a country / dial code when you enter a phone number.');
            }
            if (! empty($cid) && $nat === '') {
                $v->errors()->add('site_phone_national', 'Enter the rest of the number (without the country code), or clear the country.');
            }
            if (! empty($cid) && $nat !== '' && preg_replace('/\D/', '', $nat) === '') {
                $v->errors()->add('site_phone_national', 'Enter at least one digit for the phone number.');
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
        ];
    }
}
