<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pages')->where('template', 'default')->update(['template' => Page::TEMPLATE_FULL_WIDTH]);

        DB::table('settings')
            ->where('key', Page::SETTING_DEFAULT_TEMPLATE)
            ->where('value', 'default')
            ->update(['value' => Page::TEMPLATE_FULL_WIDTH]);
    }

    public function down(): void
    {
        // Template "default" was removed from the app; do not restore unknown prior state.
    }
};
