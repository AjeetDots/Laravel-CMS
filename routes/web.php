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
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
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

// ── Frontend Routes ──────────────────────────────────────────────
Route::get('/', [HomeController::class,             'index'])->name('home');
Route::get('/services', [ServiceController::class,          'index'])->name('services');
Route::get('/services/{slug}', [ServiceController::class,          'show'])->name('services.show');
Route::get('/finishes', [FrontendFinishController::class,   'index'])->name('finishes');
Route::get('/finishes/{slug}', [FrontendFinishController::class,   'show'])->name('finishes.show');
Route::get('/portfolio', [FrontendPortfolioController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{slug}', [FrontendPortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/gallery', [GalleryController::class,          'index'])->name('gallery');
Route::get('/contact', [ContactController::class,          'index'])->name('contact');
Route::post('/contact', [ContactController::class,          'store'])->name('contact.store');
Route::get('/blog', [BlogController::class,             'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class,        'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class,             'show'])->name('blog.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// ── Admin Auth ────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ── Protected Admin Routes ────────────────────────────────────
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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
        Route::resource('menus', MenuController::class);
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
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    });
});

// ── Dynamic Page Route (catch-all — must be last) ────────────────
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show')
    ->where('slug', '^(?!admin).*$');
