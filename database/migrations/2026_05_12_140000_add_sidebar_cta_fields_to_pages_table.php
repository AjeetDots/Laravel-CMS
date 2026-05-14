<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('sidebar_cta_title', 200)->nullable()->after('sidebar_content');
            $table->string('sidebar_cta_text', 600)->nullable()->after('sidebar_cta_title');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['sidebar_cta_title', 'sidebar_cta_text']);
        });
    }
};
