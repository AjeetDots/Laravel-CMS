<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('title_line_2', 200)->nullable()->after('title');
            $table->string('title_line_3', 200)->nullable()->after('title_line_2');
            $table->string('title_line_4', 200)->nullable()->after('title_line_3');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['title_line_2', 'title_line_3', 'title_line_4']);
        });
    }
};
