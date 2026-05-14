<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('menus', 'footer_column')) {
            return;
        }

        $roots = DB::table('menus')->whereNull('parent_id')->whereNotNull('footer_column')->get();
        $now = now();

        foreach ($roots as $root) {
            $slot = (int) $root->footer_column;
            if ($slot !== 1 && $slot !== 2) {
                continue;
            }

            DB::table('footer_nav_columns')->where('slot', $slot)->update([
                'title' => (string) $root->label,
                'updated_at' => $now,
            ]);

            $children = DB::table('menus')->where('parent_id', $root->id)->orderBy('sort_order')->get();
            DB::table('footer_nav_links')->where('slot', $slot)->delete();

            foreach ($children as $idx => $ch) {
                DB::table('footer_nav_links')->insert([
                    'slot' => $slot,
                    'label' => (string) $ch->label,
                    'url' => $ch->url,
                    'target' => $ch->target ?: '_self',
                    'sort_order' => (int) $ch->sort_order,
                    'is_active' => (bool) $ch->is_active,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('menus')->where('parent_id', $root->id)->delete();
            DB::table('menus')->where('id', $root->id)->delete();
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('footer_column');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->unsignedTinyInteger('footer_column')->nullable()->after('parent_id');
        });
    }
};
