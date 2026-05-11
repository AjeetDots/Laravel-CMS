<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Setting;
use App\Support\FrontendViewCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.frontend', function ($view) {
            try {
                $ttl = max(60, (int) config('cms.frontend_view_cache_ttl', 3600));

                $navMenus = Cache::remember(FrontendViewCache::NAV_MENUS_KEY, $ttl, function () {
                    return Menu::query()
                        ->whereNull('parent_id')
                        ->where('is_active', true)
                        ->with(['children' => function ($q) {
                            $q->where('is_active', true)->orderBy('sort_order');
                        }])
                        ->orderBy('sort_order')
                        ->get();
                });

                $settings = Cache::remember(FrontendViewCache::SETTINGS_PLUCK_KEY, $ttl, function () {
                    return Setting::query()->pluck('value', 'key');
                });
            } catch (\Exception $e) {
                $navMenus = collect();
                $settings = collect();
            }
            $view->with(compact('navMenus', 'settings'));
        });
    }
}
