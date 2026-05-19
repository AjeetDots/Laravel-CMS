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

    /*
    |--------------------------------------------------------------------------
    | Admin email change OTP (minutes)
    |--------------------------------------------------------------------------
    |
    | When an admin changes their account email, a one-time code is emailed to
    | the current address. This controls how long that code remains valid and
    | how long before a new code may be requested (resend uses the same window).
    |
    */

    'admin_email_change_otp_ttl' => (int) env('CMS_ADMIN_EMAIL_CHANGE_OTP_TTL', 15),

    /*
    |--------------------------------------------------------------------------
    | Admin login page presentation
    |--------------------------------------------------------------------------
    |
    | Hero: public URL for the left-panel image (config only; override with
    | ADMIN_LOGIN_HERO_URL in .env).
    |
    | Brand logo on the login form is resolved automatically from settings:
    | Header logo (site_logo) → Backend logo (backend_logo) → this fallback
    | (path under storage/ or a full http(s) URL). Set ADMIN_LOGIN_BRAND_LOGO
    | to empty in .env to disable the fallback when both logos are absent.
    |
    */

    'admin_login_hero_url' => env(
        'ADMIN_LOGIN_HERO_URL',
        'https://bop.24livehost.com/storage/home/sections/THirz3ff7fhF3oOT7maG1eGwIZ9L7bWLKw15OM0Y.png'
    ),

    'admin_login_brand_logo' => env(
        'ADMIN_LOGIN_BRAND_LOGO',
        'https://bop.24livehost.com/storage/settings/EyjffpYW8aV32xerRr0ZoLKrfZhw9FmngiKC6zzy.png'
    ),

    /*
    |--------------------------------------------------------------------------
    | Default placeholder image (public path)
    |--------------------------------------------------------------------------
    |
    | Shown when no image was uploaded or the stored file is missing from disk.
    | Override with CMS_DEFAULT_IMAGE in .env (path under public/, e.g. images/foo.jpg).
    |
    */

    'default_image' => env('CMS_DEFAULT_IMAGE', 'images/header-bg.jpg'),

];
