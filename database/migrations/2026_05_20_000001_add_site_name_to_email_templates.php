<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $regards = "\n<p>Regards,<br>{{site_name}}</p>";

        $templates = [
            'contact-form-admin-alert',
            'newsletter-admin-alert',
        ];

        foreach ($templates as $slug) {
            $row = DB::table('email_templates')->where('slug', $slug)->first();
            if (! $row) {
                continue;
            }

            if (str_contains((string) $row->body, '{{site_name}}')) {
                continue;
            }

            $newBody = rtrim((string) $row->body) . $regards;

            $placeholders = json_decode((string) ($row->placeholders ?? '[]'), true) ?? [];
            if (! in_array('site_name', $placeholders, true)) {
                $placeholders[] = 'site_name';
            }

            DB::table('email_templates')->where('slug', $slug)->update([
                'body' => $newBody,
                'placeholders' => json_encode(array_values($placeholders)),
            ]);
        }
    }

    public function down(): void
    {
        // Removal is intentionally a no-op — the added text is benign if left.
    }
};
