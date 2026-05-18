<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Support\ContactPageUrl;
use Illuminate\Support\Facades\Route;

/*
| Contact page GET routes (must load before the pages catch-all in web.php).
| Canonical path comes from the active CMS page slug; legacy aliases redirect.
*/

try {
    $canonicalSlug = ContactPageUrl::canonicalSlug();

    Route::get('/'.$canonicalSlug, [ContactController::class, 'index'])->name('contact');

    foreach (ContactPageUrl::legacySlugs() as $legacySlug) {
        Route::get('/'.$legacySlug, static function () {
            return redirect()->route('contact', [], 301);
        });
    }
} catch (\Throwable) {
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
}
