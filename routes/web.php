<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\FinishController as AdminFinishController;
use App\Http\Controllers\Admin\GalleryCategoryController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\FooterNavigationController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ModuleVisibilityController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserManualController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FinishController as FrontendFinishController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PortfolioController as FrontendPortfolioController;
use App\Http\Controllers\Frontend\RobotsController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

// ── SEO Utility Routes ───────────────────────────────────────────
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [RobotsController::class,  'index'])->name('robots');

// ── Dynamic slugs for template pages (reads DB; falls back to defaults if DB unavailable) ──
$_servicesSlug  = 'services';
$_finishesSlug  = 'finishes';
$_portfolioSlug = 'portfolio';
$_gallerySlug   = 'gallery';
$_blogSlug      = 'blog';
try {
    $_servicesSlug  = \App\Models\Page::where('template', 'services')->value('slug')  ?: 'services';
    $_finishesSlug  = \App\Models\Page::where('template', 'finishes')->value('slug')  ?: 'finishes';
    $_portfolioSlug = \App\Models\Page::where('template', 'portfolio')->value('slug') ?: 'portfolio';
    $_gallerySlug   = \App\Models\Page::where('template', 'gallery')->value('slug')   ?: 'gallery';
    $_blogSlug      = \App\Models\Page::where('template', 'blog')->value('slug')      ?: 'blog';
} catch (\Throwable $e) { /* first deploy / migration pending */ }

// ── Frontend Routes ──────────────────────────────────────────────
Route::get('/', [HomeController::class,             'index'])->name('home');
Route::get('/' . $_servicesSlug,            [ServiceController::class,        'index'])->middleware('cms.module:services')->name('services');
Route::get('/' . $_servicesSlug . '/{slug}',[ServiceController::class,        'show'])->middleware('cms.module:services')->name('services.show');
Route::get('/' . $_finishesSlug,            [FrontendFinishController::class, 'index'])->middleware('cms.module:finishes')->name('finishes');
Route::get('/' . $_finishesSlug . '/{slug}',[FrontendFinishController::class, 'show'])->middleware('cms.module:finishes')->name('finishes.show');
Route::get('/' . $_portfolioSlug,           [FrontendPortfolioController::class, 'index'])->middleware('cms.module:portfolio')->name('portfolio');
Route::get('/' . $_portfolioSlug . '/{slug}',[FrontendPortfolioController::class,'show'])->middleware('cms.module:portfolio')->name('portfolio.show');
Route::get('/' . $_gallerySlug,                          [GalleryController::class, 'index'])->middleware('cms.module:gallery')->name('gallery');
Route::post('/contact', [ContactController::class,          'store'])->name('contact.store');
require __DIR__.'/contact.php';
Route::get('/' . $_blogSlug,                             [BlogController::class, 'index'])->middleware('cms.module:blog')->name('blog.index');
Route::get('/' . $_blogSlug . '/category/{slug}',        [BlogController::class, 'category'])->middleware('cms.module:blog')->name('blog.category');
Route::get('/' . $_blogSlug . '/{slug}',                 [BlogController::class, 'show'])->middleware('cms.module:blog')->name('blog.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// ── Admin Auth ────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])
        ->middleware('throttle:5,1')
        ->name('password.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Protected Admin Routes ────────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('user-manual', [UserManualController::class, 'index'])->name('user-manual.index');
        Route::get('user-manual/export', [UserManualController::class, 'export'])->name('user-manual.export');
        Route::post('modules/{module}/visibility', [ModuleVisibilityController::class, 'update'])
            ->name('modules.visibility');

        // Content modules
        Route::resource('sliders', SliderController::class);
        Route::resource('services', AdminServiceController::class);
        Route::resource('finishes', AdminFinishController::class);
        Route::resource('portfolio', AdminPortfolioController::class);
        Route::resource('gallery', AdminGalleryController::class)->except(['show']);
        Route::resource('gallery-categories', GalleryCategoryController::class)->except(['show']);
        Route::resource('testimonials', TestimonialController::class);
        Route::resource('brands', BrandController::class)->except(['show']);

        // Enquiries (contact form submissions)
        Route::get('enquiries', [AdminContactController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/export', [AdminContactController::class, 'export'])->name('enquiries.export');
        Route::get('enquiries/{contact}', [AdminContactController::class, 'show'])->name('enquiries.show');
        Route::post('enquiries/{contact}/reply', [AdminContactController::class, 'reply'])->name('enquiries.reply');
        Route::delete('enquiries/{contact}', [AdminContactController::class, 'destroy'])->name('enquiries.destroy');
        // Keep legacy contact routes as aliases
        Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/export', [AdminContactController::class, 'export'])->name('contacts.export');
        Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::post('contacts/{contact}/reply', [AdminContactController::class, 'reply'])->name('contacts.reply');
        Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

        // Email templates
        Route::resource('email-templates', EmailTemplateController::class)->only(['index', 'edit', 'update']);

        // Navigation & pages
        Route::post('menus/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');
        Route::resource('menus', MenuController::class);
        Route::get('footer-navigation', [FooterNavigationController::class, 'edit'])->name('footer-navigation.edit');
        Route::put('footer-navigation', [FooterNavigationController::class, 'update'])->name('footer-navigation.update');
        Route::resource('pages', AdminPageController::class);

        // Blog
        Route::resource('blog', AdminBlogController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Newsletter
        Route::get('newsletter', [AdminNewsletterController::class, 'index'])->name('newsletter.index');
        Route::get('newsletter/export', [AdminNewsletterController::class, 'export'])->name('newsletter.export');
        Route::delete('newsletter/{subscriber}', [AdminNewsletterController::class, 'destroy'])->name('newsletter.destroy');

        // Settings & profile
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        // Cache refresh: if this URL 404s after deploy, run `php artisan route:clear` (stale bootstrap/cache/routes-*.php from `route:cache` omits new routes until rebuilt).
        // GET: bookmark / address-bar → redirect to General with an info toast. POST: runs optimize:clear + optimize, then redirect back with success toast.
        Route::get('maintenance/cache-purge', [SettingController::class, 'cachePurgeHelpRedirect'])
            ->name('maintenance.cache-purge.help');
        Route::post('maintenance/cache-purge', [SettingController::class, 'purgeApplicationCache'])
            ->middleware('throttle:20,1')
            ->name('maintenance.cache-purge');
        Route::get('theme-options/home-page', [SettingController::class, 'homePage'])->name('theme-options.home.index');
        Route::post('theme-options/home-page', [SettingController::class, 'updateHomePage'])->name('theme-options.home.update');
        Route::get('theme-options/finishes-page', [SettingController::class, 'finishesPage'])->name('theme-options.finishes.index');
        Route::post('theme-options/finishes-page', [SettingController::class, 'updateFinishesPage'])->name('theme-options.finishes.update');
        Route::get('theme-options/services-page', [SettingController::class, 'servicesPage'])->name('theme-options.services.index');
        Route::post('theme-options/services-page', [SettingController::class, 'updateServicesPage'])->name('theme-options.services.update');
        Route::get('theme-options/gallery-page', [SettingController::class, 'galleryPage'])->name('theme-options.gallery.index');
        Route::post('theme-options/gallery-page', [SettingController::class, 'updateGalleryPage'])->name('theme-options.gallery.update');
        Route::get('theme-options/portfolio-page', [SettingController::class, 'portfolioPage'])->name('theme-options.portfolio.index');
        Route::post('theme-options/portfolio-page', [SettingController::class, 'updatePortfolioPage'])->name('theme-options.portfolio.update');
        Route::get('theme-options/about-page', [SettingController::class, 'aboutPage'])->name('theme-options.about.index');
        Route::post('theme-options/about-page', [SettingController::class, 'updateAboutPage'])->name('theme-options.about.update');
        Route::get('theme-options/contact-page', [SettingController::class, 'contactPage'])->name('theme-options.contact.index');
        Route::post('theme-options/contact-page', [SettingController::class, 'updateContactPage'])->name('theme-options.contact.update');
        Route::get('theme-options/footer-newsletter', [SettingController::class, 'newsletterFooterPage'])->name('theme-options.newsletter-footer.index');
        Route::post('theme-options/footer-newsletter', [SettingController::class, 'updateNewsletterFooterPage'])->name('theme-options.newsletter-footer.update');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('profile/confirm-email', [ProfileController::class, 'confirmEmailOtp'])
            ->middleware('throttle:12,1')
            ->name('profile.confirm-email');
        Route::post('profile/resend-email-otp', [ProfileController::class, 'resendEmailOtp'])
            ->middleware('throttle:5,60')
            ->name('profile.resend-email-otp');
        Route::post('profile/cancel-email-change', [ProfileController::class, 'cancelEmailChange'])
            ->name('profile.cancel-email-change');
    });
});

// ── Dynamic Page Route (catch-all — must be last) ────────────────
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show')
    ->where('slug', '^(?!admin).*$');
