<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sliders')) {
            return;
        }
        Schema::table('sliders', function (Blueprint $table) {
            foreach (['title_line_4', 'title_line_3', 'title_line_2'] as $col) {
                if (Schema::hasColumn('sliders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sliders')) {
            return;
        }
        Schema::table('sliders', function (Blueprint $table) {
            if (! Schema::hasColumn('sliders', 'title_line_2')) {
                $table->string('title_line_2', 200)->nullable()->after('title');
            }
            if (! Schema::hasColumn('sliders', 'title_line_3')) {
                $table->string('title_line_3', 200)->nullable()->after('title_line_2');
            }
            if (! Schema::hasColumn('sliders', 'title_line_4')) {
                $table->string('title_line_4', 200)->nullable()->after('title_line_3');
            }
        });
    }
};
