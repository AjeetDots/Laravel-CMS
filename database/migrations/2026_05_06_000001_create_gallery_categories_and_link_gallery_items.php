<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->foreignId('gallery_category_id')->nullable()->after('image')->constrained('gallery_categories')->nullOnDelete();
        });

        if (Schema::hasColumn('gallery_items', 'category')) {
            $names = DB::table('gallery_items')
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->distinct()
                ->pluck('category');

            $nameToId = [];
            foreach ($names as $name) {
                $base = Str::slug($name);
                $slug = $base;
                $n = 2;
                while (DB::table('gallery_categories')->where('slug', $slug)->exists()) {
                    $slug = $base.'-'.$n++;
                }
                $id = DB::table('gallery_categories')->insertGetId([
                    'name'       => $name,
                    'slug'       => $slug,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $nameToId[$name] = $id;
            }

            foreach ($nameToId as $name => $catId) {
                DB::table('gallery_items')->where('category', $name)->update(['gallery_category_id' => $catId]);
            }

            Schema::table('gallery_items', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('gallery_categories')) {
            return;
        }

        Schema::table('gallery_items', function (Blueprint $table) {
            if (! Schema::hasColumn('gallery_items', 'category')) {
                $table->string('category')->nullable()->after('gallery_category_id');
            }
        });

        $rows = DB::table('gallery_items')
            ->join('gallery_categories', 'gallery_items.gallery_category_id', '=', 'gallery_categories.id')
            ->select('gallery_items.id', 'gallery_categories.name')
            ->get();

        foreach ($rows as $row) {
            DB::table('gallery_items')->where('id', $row->id)->update(['category' => $row->name]);
        }

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropForeign(['gallery_category_id']);
            $table->dropColumn('gallery_category_id');
        });

        Schema::dropIfExists('gallery_categories');
    }
};
