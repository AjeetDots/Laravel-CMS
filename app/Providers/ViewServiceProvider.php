<?php

namespace App\Providers;

use App\Models\FooterNavColumn;
use App\Models\FooterNavLink;
use App\Models\Menu;
use App\Models\NewsletterFooterContent;
use App\Models\Setting;
use App\Support\FrontendViewCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Child views (e.g. frontend.contact) render with their own name before/independently of
        // the layout; they still need $settings and $navMenus. The layout also needs them.
        View::composer(['layouts.frontend', 'frontend.*'], function ($view) {
            $ttl = max(60, (int) config('cms.frontend_view_cache_ttl', 3600));

            try {
                $navMenus = Menu::query()
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->with(['children' => function ($q) {
                        $q->where('is_active', true)->orderBy('sort_order');
                    }])
                    ->orderBy('sort_order')
                    ->get();

                $settingsRaw = Cache::remember(FrontendViewCache::SETTINGS_PLUCK_KEY, $ttl, function () {
                    return Setting::query()->pluck('value', 'key')->all();
                });
                $settings = collect(is_array($settingsRaw) ? $settingsRaw : []);
            } catch (\Exception $e) {
                $navMenus = collect();
                $settings = collect();
            }

            $footerNavBySlot = [
                1 => ['title' => 'Explore', 'links' => collect()],
                2 => ['title' => 'Company', 'links' => collect()],
            ];
            try {
                if (Schema::hasTable('footer_nav_columns') && Schema::hasTable('footer_nav_links')) {
                    foreach ([1, 2] as $slot) {
                        $footerNavBySlot[$slot]['title'] = FooterNavColumn::query()->where('slot', $slot)->value('title')
                            ?? ($slot === 1 ? 'Explore' : 'Company');
                        $footerNavBySlot[$slot]['links'] = FooterNavLink::query()
                            ->where('slot', $slot)
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('id')
                            ->get();
                    }
                }
            } catch (\Exception $e) {
                // keep defaults
            }

            $newsletterFooter = Cache::remember(FrontendViewCache::NEWSLETTER_FOOTER_KEY, $ttl, function () {
                return NewsletterFooterContent::viewDataWithDefaults();
            });

            $view->with(compact('navMenus', 'settings', 'footerNavBySlot', 'newsletterFooter'));
        });
    }
}
