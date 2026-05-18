<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutPageContentRequest;
use App\Http\Requests\Admin\UpdateContactPageContentRequest;
use App\Http\Requests\Admin\UpdateFinishesPageContentRequest;
use App\Http\Requests\Admin\UpdateGalleryPageContentRequest;
use App\Http\Requests\Admin\UpdateHomePageSettingRequest;
use App\Http\Requests\Admin\UpdateNewsletterFooterContentRequest;
use App\Http\Requests\Admin\UpdatePortfolioPageContentRequest;
use App\Http\Requests\Admin\UpdateServicesPageContentRequest;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\AboutPageContent;
use App\Models\ContactPageContent;
use App\Models\FinishesPageContent;
use App\Models\GalleryPageContent;
use App\Models\HomePageSection;
use App\Models\NewsletterFooterContent;
use App\Models\PhoneCountry;
use App\Models\PortfolioPageContent;
use App\Models\ServicesPageContent;
use App\Models\Setting;
use App\Support\FrontendViewCache;
use App\Support\HomePageAdminTabs;
use App\Support\ThemeContentPageTabs;
use App\Support\PhoneDigits;
use App\Support\SitePhone;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        $phoneCountries = PhoneCountry::listingQuery()->get(['id', 'iso_code', 'name', 'dial_code', 'flag_emoji']);

        return view('admin.settings.index', compact('settings', 'phoneCountries'));
    }

    public function update(UpdateSettingRequest $request)
    {
        $data = $request->validated();

        // Handle file uploads separately
        unset($data['site_logo'], $data['backend_logo'], $data['site_logo_footer'], $data['site_favicon']);

        if ($request->boolean('theme_use_defaults')) {
            Setting::set('theme_wine', null);
            Setting::set('theme_wine_dark', null);
            Setting::set('theme_gold', null);
        }
        unset($data['theme_use_defaults']);

        $smtpPassword = null;
        if (array_key_exists('mail_smtp_password', $data)) {
            $smtpPassword = trim((string) $data['mail_smtp_password']);
            unset($data['mail_smtp_password']);
        }

        $phoneCountryId = isset($data['site_phone_country_id']) ? (int) $data['site_phone_country_id'] : null;
        $phoneNational = isset($data['site_phone_national'])
            ? PhoneDigits::sanitizeNational((string) $data['site_phone_national'])
            : '';
        unset($data['site_phone_country_id'], $data['site_phone_national']);

        $whatsappCountryId = isset($data['site_whatsapp_country_id']) ? (int) $data['site_whatsapp_country_id'] : null;
        $whatsappNational = isset($data['site_whatsapp_national'])
            ? PhoneDigits::sanitizeNational((string) $data['site_whatsapp_national'])
            : '';
        unset($data['site_whatsapp_country_id'], $data['site_whatsapp_national']);

        $this->persistSitePhone($phoneCountryId ?: null, $phoneNational);
        $this->persistSiteWhatsapp($whatsappCountryId ?: null, $whatsappNational);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        if ($smtpPassword !== null && $smtpPassword !== '') {
            Setting::set('mail_smtp_password', Crypt::encryptString($smtpPassword));
        }

        foreach (['site_logo', 'backend_logo', 'site_logo_footer', 'site_favicon'] as $field) {
            if ($request->hasFile($field)) {
                $old = Setting::get($field);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $path = $request->file($field)->store('settings', 'public');
                Setting::set($field, $path);
            }
            if ($request->boolean('remove_'.$field)) {
                $old = Setting::get($field);
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                Setting::set($field, null);
            }
        }

        FrontendViewCache::forgetSettingsPluck();

        return back()->with('success', 'Site settings saved.');
    }

    /**
     * Store display phone, E.164, and country parts from the General tab.
     */
    protected function persistSitePhone(?int $countryId, string $national): void
    {
        if ($countryId === null || $national === '') {
            $prevCountryId = Setting::get('site_phone_country_id');
            $prevDisplay = Setting::get('site_phone');
            if (($prevCountryId === null || $prevCountryId === '') && $prevDisplay) {
                return;
            }

            Setting::set('site_phone', null);
            Setting::set('site_phone_e164', null);
            Setting::set('site_phone_country_id', null);
            Setting::set('site_phone_national', null);

            return;
        }

        $country = PhoneCountry::listingQuery()->whereKey($countryId)->first();
        if (! $country) {
            Setting::set('site_phone', null);
            Setting::set('site_phone_e164', null);
            Setting::set('site_phone_country_id', null);
            Setting::set('site_phone_national', null);

            return;
        }

        $nationalDigits = PhoneDigits::sanitizeNational($national);
        $dialDigits = preg_replace('/\D/', '', $country->dial_code);
        $e164 = '+'.$dialDigits.$nationalDigits;
        $display = SitePhone::formatFromCountry($country, $nationalDigits);

        Setting::set('site_phone_e164', $e164);
        Setting::set('site_phone', $display);
        Setting::set('site_phone_country_id', (string) $countryId);
        Setting::set('site_phone_national', $nationalDigits);
    }

    /**
     * Store display WhatsApp number, E.164, and country parts from the General tab.
     */
    protected function persistSiteWhatsapp(?int $countryId, string $national): void
    {
        if ($countryId === null || $national === '') {
            $prevCountryId = Setting::get('site_whatsapp_country_id');
            $prevDisplay = Setting::get('site_whatsapp');
            if (($prevCountryId === null || $prevCountryId === '') && $prevDisplay) {
                return;
            }

            Setting::set('site_whatsapp', null);
            Setting::set('site_whatsapp_e164', null);
            Setting::set('site_whatsapp_country_id', null);
            Setting::set('site_whatsapp_national', null);

            return;
        }

        $country = PhoneCountry::listingQuery()->whereKey($countryId)->first();
        if (! $country) {
            Setting::set('site_whatsapp', null);
            Setting::set('site_whatsapp_e164', null);
            Setting::set('site_whatsapp_country_id', null);
            Setting::set('site_whatsapp_national', null);

            return;
        }

        $nationalDigits = PhoneDigits::sanitizeNational($national);
        $dialDigits = preg_replace('/\D/', '', $country->dial_code);
        $e164 = '+'.$dialDigits.$nationalDigits;
        $display = SitePhone::formatFromCountry($country, $nationalDigits);

        Setting::set('site_whatsapp_e164', $e164);
        Setting::set('site_whatsapp', $display);
        Setting::set('site_whatsapp_country_id', (string) $countryId);
        Setting::set('site_whatsapp_national', $nationalDigits);
    }

    /**
     * Visiting this URL in the browser uses GET — only the form button sends POST.
     * Redirect so users are not left on a blank or confusing URL.
     */
    public function cachePurgeHelpRedirect()
    {
        return redirect()->route('admin.settings.index')
            ->with('info', 'To refresh site caches, use the "Refresh site caches" button in the top bar. Opening this link in the browser only shows this message.');
    }

    /**
     * Runs both `php artisan optimize:clear` and `php artisan optimize` in order:
     * clear all compiled/bootstrap caches, then rebuild config/routes/views/events caches.
     *
     * Always redirects afterward (typically back to the previous admin page) so the
     * success toast can show and the browser does not stay on a POST-only URL.
     */
    public function purgeApplicationCache()
    {
        try {
            // Artisan::call() returns the exit code (int). Do not use Artisan::exitCode() — it is not available on Laravel 10.
            $clearExit = Artisan::call('optimize:clear');
            $optimizeExit = Artisan::call('optimize');

            if ($clearExit !== 0 && $optimizeExit !== 0) {
                return back()->with('error', 'We could not refresh the site caches. Please try again in a moment. If this keeps happening, contact your developer.');
            }
            if ($clearExit !== 0) {
                return back()->with('warning', 'Caches were partly refreshed. If the live site still looks out of date, try again in a minute.');
            }
            if ($optimizeExit !== 0) {
                return back()->with('warning', 'Old caches were cleared, but finishing the refresh had a problem. Try again, or contact your developer if the site misbehaves.');
            }

            return back()->with('success', 'Site caches were refreshed successfully. The live website should now reflect your latest changes.');
        } catch (\Throwable $e) {
            Log::error('Admin cache purge failed', ['exception' => $e]);

            return back()->with('error', 'Something went wrong while refreshing caches. Please try again. Details have been saved for your developer.');
        }
    }

    public function homePage()
    {
        $atelierSection = HomePageSection::query()
            ->where('section_key', 'atelier')
            ->value('data') ?? [];
        if (! is_array($atelierSection)) {
            $atelierSection = [];
        }
        $finishesSection = HomePageSection::query()
            ->where('section_key', 'finishes')
            ->value('data') ?? [];
        if (! is_array($finishesSection)) {
            $finishesSection = [];
        }
        $servicesSection = HomePageSection::query()
            ->where('section_key', 'services')
            ->value('data') ?? [];
        if (! is_array($servicesSection)) {
            $servicesSection = [];
        }
        $whySection = HomePageSection::query()
            ->where('section_key', 'why')
            ->value('data') ?? [];
        if (! is_array($whySection)) {
            $whySection = [];
        }
        $processSection = HomePageSection::query()
            ->where('section_key', 'process')
            ->value('data') ?? [];
        if (! is_array($processSection)) {
            $processSection = [];
        }
        $commissionsSection = HomePageSection::query()
            ->where('section_key', 'commissions')
            ->value('data') ?? [];
        if (! is_array($commissionsSection)) {
            $commissionsSection = [];
        }
        $testimonialsSection = HomePageSection::query()
            ->where('section_key', 'testimonials')
            ->value('data') ?? [];
        if (! is_array($testimonialsSection)) {
            $testimonialsSection = [];
        }
        $beginCtaSection = HomePageSection::query()
            ->where('section_key', 'begin_cta')
            ->value('data') ?? [];
        if (! is_array($beginCtaSection)) {
            $beginCtaSection = [];
        }
        $contactBandSection = HomePageSection::query()
            ->where('section_key', 'contact_band')
            ->value('data') ?? [];
        if (! is_array($contactBandSection)) {
            $contactBandSection = [];
        }
        $brandsStripSection = HomePageSection::query()
            ->where('section_key', 'brands_strip')
            ->value('data') ?? [];
        if (! is_array($brandsStripSection)) {
            $brandsStripSection = [];
        }
        $blogPreviewSection = HomePageSection::query()
            ->where('section_key', 'blog_preview')
            ->value('data') ?? [];
        if (! is_array($blogPreviewSection)) {
            $blogPreviewSection = [];
        }

        $querySection = request()->query('section');
        $homeActiveSection = is_string($querySection) && $querySection !== ''
            ? HomePageAdminTabs::normalize($querySection)
            : HomePageAdminTabs::normalize(session('home_active_section'));

        return view('admin.theme-options.home-page', compact(
            'atelierSection',
            'finishesSection',
            'servicesSection',
            'whySection',
            'processSection',
            'commissionsSection',
            'testimonialsSection',
            'beginCtaSection',
            'contactBandSection',
            'brandsStripSection',
            'blogPreviewSection',
            'homeActiveSection',
        ));
    }

    public function finishesPage()
    {
        FinishesPageContent::query()->firstOrCreate(
            ['page_key' => FinishesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $data = FinishesPageContent::listingDataWithDefaults();
        $activeContentSection = ThemeContentPageTabs::resolve(ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM, 'intro');

        return view('admin.theme-options.finishes-page', compact('data', 'activeContentSection'));
    }

    public function updateFinishesPage(UpdateFinishesPageContentRequest $request)
    {
        $content = FinishesPageContent::query()->firstOrCreate(
            ['page_key' => FinishesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $validated = $request->validated();
        $active = ThemeContentPageTabs::normalizeIn(
            ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM,
            'intro',
            $request->input('finishes_page_active_section')
        );
        unset($validated['finishes_page_active_section']);
        $content->update(['data' => $validated]);

        return redirect()
            ->route('admin.theme-options.finishes.index', ['section' => $active])
            ->with('success', 'Finishes page saved.')
            ->with(ThemeContentPageTabs::SESSION_FLASH_KEY, $active);
    }

    public function servicesPage()
    {
        ServicesPageContent::query()->firstOrCreate(
            ['page_key' => ServicesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $data = ServicesPageContent::listingDataWithDefaults();
        $activeContentSection = ThemeContentPageTabs::resolve(ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM, 'intro');

        return view('admin.theme-options.services-page', compact('data', 'activeContentSection'));
    }

    public function updateServicesPage(UpdateServicesPageContentRequest $request)
    {
        $content = ServicesPageContent::query()->firstOrCreate(
            ['page_key' => ServicesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $validated = $request->validated();
        $active = ThemeContentPageTabs::normalizeIn(
            ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM,
            'intro',
            $request->input('services_page_active_section')
        );
        unset($validated['services_page_active_section']);
        $content->update(['data' => $validated]);

        return redirect()
            ->route('admin.theme-options.services.index', ['section' => $active])
            ->with('success', 'Services page saved.')
            ->with(ThemeContentPageTabs::SESSION_FLASH_KEY, $active);
    }

    public function galleryPage()
    {
        GalleryPageContent::query()->firstOrCreate(
            ['page_key' => GalleryPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $data = GalleryPageContent::listingDataWithDefaults();
        $activeContentSection = ThemeContentPageTabs::resolve(ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM, 'intro');

        return view('admin.theme-options.gallery-page', compact('data', 'activeContentSection'));
    }

    public function updateGalleryPage(UpdateGalleryPageContentRequest $request)
    {
        $content = GalleryPageContent::query()->firstOrCreate(
            ['page_key' => GalleryPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $validated = $request->validated();
        $active = ThemeContentPageTabs::normalizeIn(
            ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM,
            'intro',
            $request->input('gallery_page_active_section')
        );
        unset($validated['gallery_page_active_section']);
        $sectionData = is_array($content->data) ? $content->data : [];
        foreach ($validated as $key => $value) {
            $sectionData[$key] = $value;
        }
        $content->update(['data' => $sectionData]);

        return redirect()
            ->route('admin.theme-options.gallery.index', ['section' => $active])
            ->with('success', 'Gallery page saved.')
            ->with(ThemeContentPageTabs::SESSION_FLASH_KEY, $active);
    }

    public function portfolioPage()
    {
        PortfolioPageContent::query()->firstOrCreate(
            ['page_key' => PortfolioPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $data = PortfolioPageContent::listingDataWithDefaults();
        $activeContentSection = ThemeContentPageTabs::resolve(ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM, 'intro');

        return view('admin.theme-options.portfolio-page', compact('data', 'activeContentSection'));
    }

    public function updatePortfolioPage(UpdatePortfolioPageContentRequest $request)
    {
        $content = PortfolioPageContent::query()->firstOrCreate(
            ['page_key' => PortfolioPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $validated = $request->validated();
        $active = ThemeContentPageTabs::normalizeIn(
            ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM,
            'intro',
            $request->input('portfolio_page_active_section')
        );
        unset($validated['portfolio_page_active_section']);
        $content->update(['data' => $validated]);

        return redirect()
            ->route('admin.theme-options.portfolio.index', ['section' => $active])
            ->with('success', 'Portfolio page saved.')
            ->with(ThemeContentPageTabs::SESSION_FLASH_KEY, $active);
    }

    public function aboutPage()
    {
        AboutPageContent::query()->firstOrCreate(
            ['page_key' => AboutPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $data = AboutPageContent::listingDataWithDefaults();
        $activeContentSection = ThemeContentPageTabs::resolve(ThemeContentPageTabs::ABOUT, 'intro');

        return view('admin.theme-options.about-page', compact('data', 'activeContentSection'));
    }

    public function updateAboutPage(UpdateAboutPageContentRequest $request)
    {
        $content = AboutPageContent::query()->firstOrCreate(
            ['page_key' => AboutPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $sectionData = is_array($content->data) ? $content->data : [];
        $validated = $request->validated();
        $active = ThemeContentPageTabs::normalizeIn(
            ThemeContentPageTabs::ABOUT,
            'intro',
            $request->input('about_page_active_section')
        );
        unset($validated['about_page_active_section']);

        foreach ([
            'intro_eyebrow', 'intro_title', 'story_heading', 'story_body_1', 'story_body_2', 'story_body_3',
            'image_main_alt', 'image_accent_alt', 'image_studio_alt',
            'stat1_num', 'stat1_label', 'stat2_num', 'stat2_label', 'stat3_num', 'stat3_label',
            'workshop_eyebrow', 'workshop_heading', 'workshop_body', 'workshop_btn_text', 'workshop_btn_url',
        ] as $field) {
            if (array_key_exists($field, $validated)) {
                $sectionData[$field] = $validated[$field];
            }
        }

        foreach (['about_image_main' => 'image_main', 'about_image_accent' => 'image_accent', 'about_image_studio' => 'image_studio'] as $uploadField => $storageKey) {
            if ($request->hasFile($uploadField)) {
                $old = $sectionData[$storageKey] ?? null;
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $sectionData[$storageKey] = $request->file($uploadField)->store('about', 'public');
            }
            if ($request->boolean('remove_'.$uploadField)) {
                $old = $sectionData[$storageKey] ?? null;
                if ($old && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $sectionData[$storageKey] = null;
            }
        }

        $content->update(['data' => $sectionData]);

        return redirect()
            ->route('admin.theme-options.about.index', ['section' => $active])
            ->with('success', 'About page saved.')
            ->with(ThemeContentPageTabs::SESSION_FLASH_KEY, $active);
    }

    public function contactPage()
    {
        ContactPageContent::query()->firstOrCreate(
            ['page_key' => ContactPageContent::PAGE_KEY],
            ['data' => []]
        );
        $data = ContactPageContent::viewDataWithDefaults();
        $activeContentSection = ThemeContentPageTabs::resolve(ThemeContentPageTabs::CONTACT, 'hero');

        return view('admin.theme-options.contact-page', compact('data', 'activeContentSection'));
    }

    public function updateContactPage(UpdateContactPageContentRequest $request)
    {
        $content = ContactPageContent::query()->firstOrCreate(
            ['page_key' => ContactPageContent::PAGE_KEY],
            ['data' => []]
        );
        $sectionData = is_array($content->data) ? $content->data : [];
        $validated = $request->validated();
        $active = ThemeContentPageTabs::normalizeIn(
            ThemeContentPageTabs::CONTACT,
            'hero',
            $request->input('contact_page_active_section')
        );
        unset($validated['contact_page_active_section']);

        foreach ([
            'hero_line_1', 'hero_line_2', 'hero_cta',
            'info_eyebrow', 'info_heading_1', 'info_heading_2', 'info_lead',
            'studio_label', 'studio_body', 'hours_label', 'hours_body', 'appointment_line',
            'form_title', 'form_error_intro',
            'name_placeholder', 'email_placeholder', 'phone_field_label', 'national_placeholder',
            'message_placeholder', 'submit_label', 'map_embed_url',
        ] as $field) {
            if (array_key_exists($field, $validated)) {
                $sectionData[$field] = $validated[$field];
            }
        }
        $sectionData['show_map'] = $request->boolean('show_map');
        unset($sectionData['fallback_phone_display'], $sectionData['fallback_whatsapp_label']);

        if ($request->hasFile('contact_hero_bg_image')) {
            $old = $sectionData['hero_bg_image'] ?? null;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $sectionData['hero_bg_image'] = $request->file('contact_hero_bg_image')->store('contact', 'public');
        }
        if ($request->boolean('remove_contact_hero_bg_image')) {
            $old = $sectionData['hero_bg_image'] ?? null;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            $sectionData['hero_bg_image'] = '';
        }

        $content->update(['data' => $sectionData]);

        return redirect()
            ->route('admin.theme-options.contact.index', ['section' => $active])
            ->with('success', 'Contact page saved.')
            ->with(ThemeContentPageTabs::SESSION_FLASH_KEY, $active);
    }

    public function newsletterFooterPage()
    {
        NewsletterFooterContent::query()->firstOrCreate(
            ['page_key' => NewsletterFooterContent::PAGE_KEY],
            ['data' => []]
        );
        $data = NewsletterFooterContent::viewDataWithDefaults();

        return view('admin.theme-options.newsletter-footer', compact('data'));
    }

    public function updateNewsletterFooterPage(UpdateNewsletterFooterContentRequest $request)
    {
        $content = NewsletterFooterContent::query()->firstOrCreate(
            ['page_key' => NewsletterFooterContent::PAGE_KEY],
            ['data' => []]
        );
        $sectionData = is_array($content->data) ? $content->data : [];
        $validated = $request->validated();

        foreach (array_keys($validated) as $field) {
            $sectionData[$field] = $validated[$field];
        }

        $content->update(['data' => $sectionData]);
        FrontendViewCache::forgetNewsletterFooter();

        return back()->with('success', 'Footer newsletter saved.');
    }

    public function updateHomePage(UpdateHomePageSettingRequest $request)
    {
        $data = $request->validated();

        $section = HomePageSection::query()->firstOrCreate(['section_key' => 'atelier'], ['data' => []]);
        $sectionData = is_array($section->data) ? $section->data : [];

        $sectionData['is_enabled'] = $request->boolean('home_atelier_is_enabled');
        $sectionData['kicker'] = $data['home_atelier_kicker'] ?? null;
        $sectionData['heading_line_1'] = $data['home_atelier_heading_line_1'] ?? null;
        $sectionData['heading_line_2'] = $data['home_atelier_heading_line_2'] ?? null;
        $sectionData['heading_line_3'] = $data['home_atelier_heading_line_3'] ?? null;
        $sectionData['body'] = $data['home_atelier_body'] ?? null;
        $sectionData['cta_text'] = $data['home_atelier_cta_text'] ?? null;
        $sectionData['cta_url'] = $data['home_atelier_cta_url'] ?? null;
        $sectionData['booking_label'] = $data['home_atelier_booking_label'] ?? null;
        $sectionData['booking_url'] = $data['home_atelier_booking_url'] ?? null;
        unset($sectionData['booking_text']);

        if ($request->hasFile('home_atelier_primary_image')) {
            $oldPrimary = $sectionData['primary_image'] ?? null;
            if ($oldPrimary && Storage::disk('public')->exists($oldPrimary)) {
                Storage::disk('public')->delete($oldPrimary);
            }
            $sectionData['primary_image'] = $request->file('home_atelier_primary_image')->store('home/sections', 'public');
        } elseif ($request->boolean('remove_home_atelier_primary_image')) {
            $oldPrimary = $sectionData['primary_image'] ?? null;
            if ($oldPrimary && Storage::disk('public')->exists($oldPrimary)) {
                Storage::disk('public')->delete($oldPrimary);
            }
            $sectionData['primary_image'] = null;
        }

        if ($request->hasFile('home_atelier_secondary_image')) {
            $oldSecondary = $sectionData['secondary_image'] ?? null;
            if ($oldSecondary && Storage::disk('public')->exists($oldSecondary)) {
                Storage::disk('public')->delete($oldSecondary);
            }
            $sectionData['secondary_image'] = $request->file('home_atelier_secondary_image')->store('home/sections', 'public');
        } elseif ($request->boolean('remove_home_atelier_secondary_image')) {
            $oldSecondary = $sectionData['secondary_image'] ?? null;
            if ($oldSecondary && Storage::disk('public')->exists($oldSecondary)) {
                Storage::disk('public')->delete($oldSecondary);
            }
            $sectionData['secondary_image'] = null;
        }

        $section->update(['data' => $sectionData]);

        $finishes = HomePageSection::query()->firstOrCreate(['section_key' => 'finishes'], ['data' => []]);
        $finishesData = is_array($finishes->data) ? $finishes->data : [];
        $finishesData['is_enabled'] = $request->boolean('home_finishes_is_enabled');
        $finishesData['eyebrow'] = $data['home_finishes_eyebrow'] ?? null;
        $finishesData['heading_line_1'] = $data['home_finishes_heading_line_1'] ?? null;
        $finishesData['heading_line_2'] = $data['home_finishes_heading_line_2'] ?? null;
        $finishesData['card_label'] = $data['home_finishes_card_label'] ?? null;
        $finishesData['button_text'] = $data['home_finishes_button_text'] ?? null;
        $finishesData['button_url'] = $data['home_finishes_button_url'] ?? null;
        $finishes->update(['data' => $finishesData]);

        $services = HomePageSection::query()->firstOrCreate(['section_key' => 'services'], ['data' => []]);
        $servicesData = is_array($services->data) ? $services->data : [];
        $servicesData['is_enabled'] = $request->boolean('home_services_is_enabled');
        $servicesData['eyebrow'] = $data['home_services_eyebrow'] ?? null;
        $servicesData['heading_line_1'] = $data['home_services_heading_line_1'] ?? null;
        $servicesData['heading_line_2'] = $data['home_services_heading_line_2'] ?? null;
        $servicesData['button_text'] = $data['home_services_button_text'] ?? null;
        $servicesData['button_url'] = $data['home_services_button_url'] ?? null;
        $servicesData['card_link_text'] = $data['home_services_card_link_text'] ?? null;
        $services->update(['data' => $servicesData]);

        $why = HomePageSection::query()->firstOrCreate(['section_key' => 'why'], ['data' => []]);
        $whyData = is_array($why->data) ? $why->data : [];
        $whyData['is_enabled'] = $request->boolean('home_why_is_enabled');
        $whyData['eyebrow'] = $data['home_why_eyebrow'] ?? null;
        $whyData['heading'] = $data['home_why_heading'] ?? null;
        $whyData['lead'] = $data['home_why_lead'] ?? null;
        $whyData['cards'] = [
            [
                'icon' => $data['home_why_card_1_icon'] ?? null,
                'title' => $data['home_why_card_1_title'] ?? null,
                'desc' => $data['home_why_card_1_desc'] ?? null,
            ],
            [
                'icon' => $data['home_why_card_2_icon'] ?? null,
                'title' => $data['home_why_card_2_title'] ?? null,
                'desc' => $data['home_why_card_2_desc'] ?? null,
            ],
            [
                'icon' => $data['home_why_card_3_icon'] ?? null,
                'title' => $data['home_why_card_3_title'] ?? null,
                'desc' => $data['home_why_card_3_desc'] ?? null,
            ],
            [
                'icon' => $data['home_why_card_4_icon'] ?? null,
                'title' => $data['home_why_card_4_title'] ?? null,
                'desc' => $data['home_why_card_4_desc'] ?? null,
            ],
        ];
        $why->update(['data' => $whyData]);

        $process = HomePageSection::query()->firstOrCreate(['section_key' => 'process'], ['data' => []]);
        $processData = is_array($process->data) ? $process->data : [];
        $processData['is_enabled'] = $request->boolean('home_process_is_enabled');
        $processData['eyebrow'] = $data['home_process_eyebrow'] ?? null;
        $processData['heading_line_1'] = $data['home_process_heading_line_1'] ?? null;
        $processData['heading_line_2'] = $data['home_process_heading_line_2'] ?? null;
        $processData['steps'] = [
            ['num' => $data['home_process_step_1_num'] ?? null, 'title' => $data['home_process_step_1_title'] ?? null, 'desc' => $data['home_process_step_1_desc'] ?? null],
            ['num' => $data['home_process_step_2_num'] ?? null, 'title' => $data['home_process_step_2_title'] ?? null, 'desc' => $data['home_process_step_2_desc'] ?? null],
            ['num' => $data['home_process_step_3_num'] ?? null, 'title' => $data['home_process_step_3_title'] ?? null, 'desc' => $data['home_process_step_3_desc'] ?? null],
            ['num' => $data['home_process_step_4_num'] ?? null, 'title' => $data['home_process_step_4_title'] ?? null, 'desc' => $data['home_process_step_4_desc'] ?? null],
        ];
        $process->update(['data' => $processData]);

        $commissions = HomePageSection::query()->firstOrCreate(['section_key' => 'commissions'], ['data' => []]);
        $commissionsData = is_array($commissions->data) ? $commissions->data : [];
        $commissionsData['is_enabled'] = $request->boolean('home_commissions_is_enabled');
        $commissionsData['eyebrow'] = $data['home_commissions_eyebrow'] ?? null;
        $commissionsData['heading_line_1'] = $data['home_commissions_heading_line_1'] ?? null;
        $commissionsData['button_text'] = $data['home_commissions_button_text'] ?? null;
        $commissionsData['button_url'] = $data['home_commissions_button_url'] ?? null;
        $commissions->update(['data' => $commissionsData]);

        $testimonials = HomePageSection::query()->firstOrCreate(['section_key' => 'testimonials'], ['data' => []]);
        $testimonialsData = is_array($testimonials->data) ? $testimonials->data : [];
        $testimonialsData['is_enabled'] = $request->boolean('home_testimonials_is_enabled');
        $testimonialsData['left_eyebrow'] = $data['home_testimonials_left_eyebrow'] ?? null;
        $testimonialsData['left_headline'] = $data['home_testimonials_left_headline'] ?? null;
        $testimonialsData['right_eyebrow'] = $data['home_testimonials_right_eyebrow'] ?? null;

        if ($request->hasFile('home_testimonials_left_image')) {
            $oldLeftImage = $testimonialsData['left_image'] ?? null;
            if ($oldLeftImage && Storage::disk('public')->exists($oldLeftImage)) {
                Storage::disk('public')->delete($oldLeftImage);
            }
            $testimonialsData['left_image'] = $request->file('home_testimonials_left_image')->store('home/sections', 'public');
        } elseif ($request->boolean('remove_home_testimonials_left_image')) {
            $oldLeftImage = $testimonialsData['left_image'] ?? null;
            if ($oldLeftImage && Storage::disk('public')->exists($oldLeftImage)) {
                Storage::disk('public')->delete($oldLeftImage);
            }
            $testimonialsData['left_image'] = null;
        }

        $testimonials->update(['data' => $testimonialsData]);

        $beginCta = HomePageSection::query()->firstOrCreate(['section_key' => 'begin_cta'], ['data' => []]);
        $beginCtaData = is_array($beginCta->data) ? $beginCta->data : [];
        $beginCtaData['is_enabled'] = $request->boolean('home_begin_cta_is_enabled');
        $beginCtaData['eyebrow'] = $data['home_begin_cta_eyebrow'] ?? null;
        $beginCtaData['title_line_1'] = $data['home_begin_cta_title_line_1'] ?? null;
        $beginCtaData['title_line_2'] = $data['home_begin_cta_title_line_2'] ?? null;
        $beginCtaData['primary_btn_text'] = $data['home_begin_cta_primary_btn_text'] ?? null;
        $beginCtaData['primary_btn_url'] = $data['home_begin_cta_primary_btn_url'] ?? null;
        $beginCtaData['secondary_btn_text'] = $data['home_begin_cta_secondary_btn_text'] ?? null;
        $beginCtaData['secondary_btn_url'] = $data['home_begin_cta_secondary_btn_url'] ?? null;

        if ($request->hasFile('home_begin_cta_bg_image')) {
            $oldBgImage = $beginCtaData['bg_image'] ?? null;
            if ($oldBgImage && Storage::disk('public')->exists($oldBgImage)) {
                Storage::disk('public')->delete($oldBgImage);
            }
            $beginCtaData['bg_image'] = $request->file('home_begin_cta_bg_image')->store('home/sections', 'public');
        } elseif ($request->boolean('remove_home_begin_cta_bg_image')) {
            $oldBgImage = $beginCtaData['bg_image'] ?? null;
            if ($oldBgImage && Storage::disk('public')->exists($oldBgImage)) {
                Storage::disk('public')->delete($oldBgImage);
            }
            $beginCtaData['bg_image'] = null;
        }

        $beginCta->update(['data' => $beginCtaData]);

        $contactBand = HomePageSection::query()->firstOrCreate(['section_key' => 'contact_band'], ['data' => []]);
        $contactBandData = is_array($contactBand->data) ? $contactBand->data : [];
        $contactBandData['is_enabled'] = $request->boolean('home_contact_band_is_enabled');
        $contactBandData['eyebrow'] = $data['home_contact_band_eyebrow'] ?? null;
        $contactBandData['heading'] = $data['home_contact_band_heading'] ?? null;
        $contactBandData['panel_title'] = $data['home_contact_band_panel_title'] ?? null;
        $contactBandData['name_placeholder'] = $data['home_contact_band_name_placeholder'] ?? null;
        $contactBandData['email_placeholder'] = $data['home_contact_band_email_placeholder'] ?? null;
        $contactBandData['phone_placeholder'] = $data['home_contact_band_phone_placeholder'] ?? null;
        $contactBandData['message_placeholder'] = $data['home_contact_band_message_placeholder'] ?? null;
        $contactBandData['submit_text'] = $data['home_contact_band_submit_text'] ?? null;
        unset($contactBandData['subject']);

        if ($request->hasFile('home_contact_band_visual_image')) {
            $oldVisualImage = $contactBandData['visual_image'] ?? null;
            if ($oldVisualImage && Storage::disk('public')->exists($oldVisualImage)) {
                Storage::disk('public')->delete($oldVisualImage);
            }
            $contactBandData['visual_image'] = $request->file('home_contact_band_visual_image')->store('home/sections', 'public');
        } elseif ($request->boolean('remove_home_contact_band_visual_image')) {
            $oldVisualImage = $contactBandData['visual_image'] ?? null;
            if ($oldVisualImage && Storage::disk('public')->exists($oldVisualImage)) {
                Storage::disk('public')->delete($oldVisualImage);
            }
            $contactBandData['visual_image'] = null;
        }

        $contactBand->update(['data' => $contactBandData]);

        $brandsStrip = HomePageSection::query()->firstOrCreate(['section_key' => 'brands_strip'], ['data' => []]);
        $brandsStripData = is_array($brandsStrip->data) ? $brandsStrip->data : [];
        $brandsStripData['is_enabled'] = $request->boolean('home_brands_strip_is_enabled');
        $brandsStripData['kicker'] = $data['home_brands_strip_kicker'] ?? null;
        $brandsStripData['title_line_1'] = $data['home_brands_strip_title_line_1'] ?? null;
        $brandsStripData['title_line_2'] = $data['home_brands_strip_title_line_2'] ?? null;
        $brandsStripData['marquee_segments'] = isset($data['home_brands_strip_marquee_segments'])
            ? (int) $data['home_brands_strip_marquee_segments']
            : null;
        $brandsStrip->update(['data' => $brandsStripData]);

        $blogPreview = HomePageSection::query()->firstOrCreate(['section_key' => 'blog_preview'], ['data' => []]);
        $blogPreviewData = is_array($blogPreview->data) ? $blogPreview->data : [];
        $blogPreviewData['is_enabled'] = $request->boolean('home_blog_preview_is_enabled');
        $blogPreviewData['eyebrow'] = $data['home_blog_preview_eyebrow'] ?? null;
        $blogPreviewData['heading'] = $data['home_blog_preview_heading'] ?? null;
        $blogPreviewData['button_text'] = $data['home_blog_preview_button_text'] ?? null;
        $blogPreviewData['button_url'] = $data['home_blog_preview_button_url'] ?? null;
        $blogPreviewData['read_more_text'] = $data['home_blog_preview_read_more_text'] ?? null;
        $blogPreview->update(['data' => $blogPreviewData]);

        $activeSection = HomePageAdminTabs::normalize($request->string('home_active_section')->toString());

        return redirect()
            ->route('admin.theme-options.home.index', ['section' => $activeSection])
            ->with('success', 'Home Page saved.')
            ->with('home_active_section', $activeSection);
    }
}
