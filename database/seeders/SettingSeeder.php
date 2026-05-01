<?php
namespace Database\Seeders;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder {
    public function run(): void {
        $settings = [
            ['key' => 'site_name', 'value' => 'ProServices'],
            ['key' => 'site_tagline', 'value' => 'Professional Services Company'],
            ['key' => 'site_email', 'value' => 'info@proservices.com'],
            ['key' => 'site_phone', 'value' => '+1 (555) 123-4567'],
            ['key' => 'site_address', 'value' => '123 Business Ave, New York, NY 10001'],
            ['key' => 'footer_about', 'value' => 'We are a professional services company dedicated to delivering innovative solutions that drive business growth and success.'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com'],
        ];
        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value']]);
        }
    }
}
