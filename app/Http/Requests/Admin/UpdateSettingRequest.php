<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name'        => 'required|string|max:100',
            'site_tagline'     => 'nullable|string|max:200',
            'site_email'       => 'nullable|email',
            'site_phone'       => 'nullable|string|max:30',
            'site_address'     => 'nullable|string|max:500',
            'footer_about'     => 'nullable|string|max:1000',
            'social_facebook'  => 'nullable|url',
            'social_twitter'   => 'nullable|url',
            'social_linkedin'  => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'site_logo'        => 'nullable|image|max:2048',
            'site_logo_footer' => 'nullable|image|max:2048',
        ];
    }
}
