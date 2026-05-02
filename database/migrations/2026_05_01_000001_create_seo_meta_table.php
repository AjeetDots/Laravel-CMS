<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable'); // adds seoable_type, seoable_id + index

            // Core SEO
            $table->string('meta_title', 70)->nullable();
            $table->string('meta_description', 165)->nullable();
            $table->string('focus_keyword', 100)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->enum('robots_index', ['index', 'noindex'])->default('index');
            $table->enum('robots_follow', ['follow', 'nofollow'])->default('follow');

            // Open Graph
            $table->string('og_title', 95)->nullable();
            $table->string('og_description', 200)->nullable();
            $table->string('og_image', 500)->nullable();

            // Twitter Card
            $table->enum('twitter_card', ['summary', 'summary_large_image'])->default('summary_large_image');
            $table->string('twitter_title', 70)->nullable();
            $table->string('twitter_description', 200)->nullable();
            $table->string('twitter_image', 500)->nullable();

            // JSON-LD structured data
            $table->text('schema_markup')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
