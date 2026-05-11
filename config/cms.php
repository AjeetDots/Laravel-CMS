<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin panel product name (fallback)
    |--------------------------------------------------------------------------
    |
    | Shown in document titles and admin chrome when Site name (settings) is
    | empty. Override with CMS_PANEL_NAME in .env (e.g. BOP CMS).
    |
    */

    'panel_name' => env('CMS_PANEL_NAME', 'BOP CMS'),

    /*
    |--------------------------------------------------------------------------
    | Public layout view composer cache (seconds)
    |--------------------------------------------------------------------------
    |
    | Menus and settings pluck are cached for this TTL on the frontend layout
    | only. Cleared when site settings or menus are saved in the admin panel.
    |
    */

    'frontend_view_cache_ttl' => (int) env('CMS_FRONTEND_VIEW_CACHE_TTL', 3600),

];
