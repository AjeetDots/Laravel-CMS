<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Assign fixed templates to built-in pages and ensure Blog / Gallery exist.
     * Safe to run multiple times (upsert / ignore duplicates).
     */
    public function up(): void
    {
        $now = now();

        // 1. Assign fixed templates to pages that already exist by slug
        $slugToTemplate = [
            '/'          => 'home',
            'home'       => 'home',
            'services'   => 'services',
            'finishes'   => 'finishes',
            'portfolio'  => 'portfolio',
            'gallery'    => 'gallery',
            'blog'       => 'blog',
            'about'      => 'about',
            'about-us'   => 'about',
            'contact'    => 'contact',
            'contact-us' => 'contact',
        ];

        foreach ($slugToTemplate as $slug => $template) {
            DB::table('pages')
                ->whereNull('deleted_at')
                ->where('slug', $slug)
                ->update(['template' => $template]);
        }

        // 2. Create Blog page if none with template=blog exists
        $hasBlog = DB::table('pages')->whereNull('deleted_at')->where('template', 'blog')->exists();
        if (! $hasBlog) {
            DB::table('pages')->insert([
                'title'      => 'Blog',
                'slug'       => 'blog',
                'template'   => 'blog',
                'is_active'  => true,
                'body_order' => 'content_first',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 3. Create Gallery page if none with template=gallery exists
        $hasGallery = DB::table('pages')->whereNull('deleted_at')->where('template', 'gallery')->exists();
        if (! $hasGallery) {
            DB::table('pages')->insert([
                'title'      => 'Gallery',
                'slug'       => 'gallery',
                'template'   => 'gallery',
                'is_active'  => true,
                'body_order' => 'content_first',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $listingTemplates = ['home', 'services', 'finishes', 'portfolio', 'gallery', 'blog'];
        DB::table('pages')
            ->whereNull('deleted_at')
            ->whereIn('template', $listingTemplates)
            ->update(['template' => 'full-width']);
    }
};
