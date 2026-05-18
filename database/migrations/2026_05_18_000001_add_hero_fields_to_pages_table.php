<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('hero_eyebrow', 120)->nullable()->after('slug');
            $table->string('hero_lede', 1000)->nullable()->after('hero_eyebrow');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['hero_eyebrow', 'hero_lede']);
        });
    }
};
