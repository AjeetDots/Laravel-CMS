<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('footer_nav_columns', function (Blueprint $table) {
            $table->unsignedTinyInteger('slot')->primary();
            $table->string('title', 120);
            $table->timestamps();
        });

        Schema::create('footer_nav_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('slot');
            $table->string('label', 150);
            $table->string('url', 500)->nullable();
            $table->string('target', 10)->default('_self');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['slot', 'sort_order']);
        });

        $now = now();
        DB::table('footer_nav_columns')->insertOrIgnore([
            ['slot' => 1, 'title' => 'Explore', 'created_at' => $now, 'updated_at' => $now],
            ['slot' => 2, 'title' => 'Company', 'created_at' => $now, 'updated_at' => $now],
        ]);

        if (DB::table('footer_nav_links')->count() === 0) {
            $rows = [
                [1, 'Finishes', '/finishes', '_self', 1],
                [1, 'Services', '/services', '_self', 2],
                [1, 'Gallery', '/gallery', '_self', 3],
                [1, 'Blog', '/blog', '_self', 4],
                [2, 'Home', '/', '_self', 1],
                [2, 'About Us', '/about', '_self', 2],
                [2, 'Contact', '/contact', '_self', 3],
                [2, 'Privacy Policy', '/privacy-policy', '_self', 4],
                [2, 'Terms & Conditions', '/terms-and-conditions', '_self', 5],
                [2, 'Cookie Policy', '/cookie-policy', '_self', 6],
            ];
            foreach ($rows as $r) {
                DB::table('footer_nav_links')->insert([
                    'slot' => $r[0],
                    'label' => $r[1],
                    'url' => $r[2],
                    'target' => $r[3],
                    'sort_order' => $r[4],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_nav_links');
        Schema::dropIfExists('footer_nav_columns');
    }
};
