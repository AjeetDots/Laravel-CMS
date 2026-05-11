<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin panel product name
    |--------------------------------------------------------------------------
    |
    | Shown in document titles and the admin chrome. Distinct from the public
    | site name (Settings → General / APP_NAME).
    |
    */

    'panel_name' => env('CMS_PANEL_NAME', 'Laravel CMS'),

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
