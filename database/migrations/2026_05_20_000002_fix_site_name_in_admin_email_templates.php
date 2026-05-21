<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $regards = "\n<p>Regards,<br>{{site_name}}</p>";

        $types = [
            'contact_admin',
            'newsletter_admin',
        ];

        foreach ($types as $type) {
            $rows = DB::table('email_templates')->where('template_type', $type)->get();
            foreach ($rows as $row) {
                if (str_contains((string) $row->body, '{{site_name}}')) {
                    continue;
                }

                $newBody = rtrim((string) $row->body) . $regards;

                $placeholders = json_decode((string) ($row->placeholders ?? '[]'), true) ?? [];
                if (! in_array('site_name', $placeholders, true)) {
                    $placeholders[] = 'site_name';
                }

                DB::table('email_templates')->where('id', $row->id)->update([
                    'body' => $newBody,
                    'placeholders' => json_encode(array_values($placeholders)),
                ]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
