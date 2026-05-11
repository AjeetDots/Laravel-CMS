<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutPageContentRequest;
use App\Http\Requests\Admin\UpdateContactPageContentRequest;
use App\Http\Requests\Admin\UpdateFinishesPageContentRequest;
use App\Http\Requests\Admin\UpdateGalleryPageContentRequest;
use App\Http\Requests\Admin\UpdateHomePageSettingRequest;
use App\Http\Requests\Admin\UpdatePortfolioPageContentRequest;
use App\Http\Requests\Admin\UpdateServicesPageContentRequest;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\AboutPageContent;
use App\Models\ContactPageContent;
use App\Models\FinishesPageContent;
use App\Models\GalleryPageContent;
use App\Models\HomePageSection;
use App\Models\PhoneCountry;
use App\Models\PortfolioPageContent;
use App\Models\ServicesPageContent;
use App\Models\Setting;
use App\Support\SitePhone;
use Illuminate\Support\Facades\Artisan;
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

        $phoneCountryId = isset($data['site_phone_country_id']) ? (int) $data['site_phone_country_id'] : null;
        $phoneNational = isset($data['site_phone_national']) ? trim((string) $data['site_phone_national']) : '';
        unset($data['site_phone_country_id'], $data['site_phone_national']);

        $this->persistSitePhone($phoneCountryId ?: null, $phoneNational);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
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

        $nationalDigits = preg_replace('/\D/', '', $national);
        $dialDigits = preg_replace('/\D/', '', $country->dial_code);
        $e164 = '+'.$dialDigits.$nationalDigits;
        $display = SitePhone::formatFromCountry($country, $national);

        Setting::set('site_phone_e164', $e164);
        Setting::set('site_phone', $display);
        Setting::set('site_phone_country_id', (string) $countryId);
        Setting::set('site_phone_national', $national);
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

            return back()->with('success', 'Done — site caches were refreshed. Your latest changes should now appear on the website.');
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
        $commissionsSection = HomePageSection::query()
            ->where('section_key', 'commissions')
            ->value('data') ?? [];
        if (! is_array($commissionsSection)) {
            $commissionsSection = [];
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

        return view('admin.theme-options.home-page', compact('atelierSection', 'finishesSection', 'servicesSection', 'commissionsSection', 'whySection', 'processSection', 'beginCtaSection', 'contactBandSection', 'brandsStripSection', 'blogPreviewSection'));
    }

    public function finishesPage()
    {
        $content = FinishesPageContent::query()->firstOrCreate(
            ['page_key' => FinishesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $stored = is_array($content->data) ? $content->data : [];
        $defaults = [
            'intro_eyebrow' => 'Our finishes',
            'intro_title' => 'Six finishes. One obsession with the surface.',
            'intro_body' => 'Every finish is mixed, applied and polished by hand. Bespoke colours are developed in studio against samples of your space, your light and your interiors.',
            'card_label_fallback' => 'Hand-crafted decorative finish',
            'empty_message' => 'No finishes have been published yet.',
            'empty_btn_text' => 'Get in touch',
            'empty_btn_url' => '',
            'bottom_eyebrow' => 'Begin',
            'bottom_heading' => 'Not sure which finish suits your space?',
            'bottom_body' => 'Tell us about the room and we\'ll prepare hand-made samples for your light.',
            'bottom_btn_text' => 'Request samples',
            'bottom_btn_url' => '',
        ];
        $data = [];
        foreach ($defaults as $key => $default) {
            $data[$key] = array_key_exists($key, $stored) ? $stored[$key] : $default;
        }

        return view('admin.theme-options.finishes-page', compact('data'));
    }

    public function updateFinishesPage(UpdateFinishesPageContentRequest $request)
    {
        $content = FinishesPageContent::query()->firstOrCreate(
            ['page_key' => FinishesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $content->update(['data' => $request->validated()]);

        return back()->with('success', 'Finishes page saved.');
    }

    public function servicesPage()
    {
        $content = ServicesPageContent::query()->firstOrCreate(
            ['page_key' => ServicesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $stored = is_array($content->data) ? $content->data : [];
        $defaults = [
            'intro_eyebrow' => 'Services',
            'intro_title' => "Three disciplines,\napplied with the\nsame obsession.",
            'intro_body' => 'From a single feature wall to a full residence, we work alongside designers, architects and private clients to deliver finishes of lasting beauty.',
            'service_cta_prefix' => 'Enquire about',
            'empty_message' => 'No services available yet.',
            'empty_btn_text' => 'Get in touch',
            'empty_btn_url' => '',
            'bottom_eyebrow' => 'BEGIN',
            'bottom_heading' => "Bring your space.\nWe'll bring the finish.",
            'bottom_body' => '',
            'bottom_btn_text' => 'Get in touch',
            'bottom_btn_url' => '',
        ];
        $data = [];
        foreach ($defaults as $key => $default) {
            $data[$key] = array_key_exists($key, $stored) ? $stored[$key] : $default;
        }

        return view('admin.theme-options.services-page', compact('data'));
    }

    public function updateServicesPage(UpdateServicesPageContentRequest $request)
    {
        $content = ServicesPageContent::query()->firstOrCreate(
            ['page_key' => ServicesPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $content->update(['data' => $request->validated()]);

        return back()->with('success', 'Services page saved.');
    }

    public function galleryPage()
    {
        $content = GalleryPageContent::query()->firstOrCreate(
            ['page_key' => GalleryPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $stored = is_array($content->data) ? $content->data : [];
        $defaults = [
            'intro_eyebrow' => 'Portfolio',
            'intro_title' => 'A quiet record of recent work.',
            'filter_all_label' => 'All',
            'grid_category_fallback' => 'Portfolio',
            'empty_message' => 'No gallery items yet.',
            'empty_btn_text' => '',
            'empty_btn_url' => '',
            'bottom_heading' => 'Like what you see?',
            'bottom_btn_text' => 'Start a project',
            'bottom_btn_url' => '',
        ];
        $data = [];
        foreach ($defaults as $key => $default) {
            $data[$key] = array_key_exists($key, $stored) ? $stored[$key] : $default;
        }

        return view('admin.theme-options.gallery-page', compact('data'));
    }

    public function updateGalleryPage(UpdateGalleryPageContentRequest $request)
    {
        $content = GalleryPageContent::query()->firstOrCreate(
            ['page_key' => GalleryPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $content->update(['data' => $request->validated()]);

        return back()->with('success', 'Gallery page saved.');
    }

    public function portfolioPage()
    {
        $content = PortfolioPageContent::query()->firstOrCreate(
            ['page_key' => PortfolioPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $stored = is_array($content->data) ? $content->data : [];
        $defaults = [
            'intro_eyebrow' => 'Completed work',
            'intro_title' => 'Portfolio',
            'intro_body' => 'Project-based inspiration — reference imagery and real commissions. Explore by tag or open a project for the full story.',
            'breadcrumb_current' => 'Portfolio',
            'filter_all_label' => 'All',
            'card_link_text' => 'View project',
            'label_real_project' => 'Real project',
            'label_reference' => 'Reference',
            'empty_message' => 'No portfolio entries yet.',
            'empty_btn_text' => '',
            'empty_btn_url' => '',
            'bottom_heading' => 'Planning something similar?',
            'bottom_body' => 'Share your brief and we\'ll outline timelines and options.',
            'bottom_btn_text' => 'Get in touch',
            'bottom_btn_url' => '',
        ];
        $data = [];
        foreach ($defaults as $key => $default) {
            $data[$key] = array_key_exists($key, $stored) ? $stored[$key] : $default;
        }

        return view('admin.theme-options.portfolio-page', compact('data'));
    }

    public function updatePortfolioPage(UpdatePortfolioPageContentRequest $request)
    {
        $content = PortfolioPageContent::query()->firstOrCreate(
            ['page_key' => PortfolioPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $content->update(['data' => $request->validated()]);

        return back()->with('success', 'Portfolio page saved.');
    }

    public function aboutPage()
    {
        $content = AboutPageContent::query()->firstOrCreate(
            ['page_key' => AboutPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $stored = is_array($content->data) ? $content->data : [];
        $defaults = [
            'intro_eyebrow' => 'About the atelier',
            'intro_title' => "A studio of artisans,\na craft of patience.",
            'story_heading' => 'Our story',
            'story_body_1' => 'Trained in the lime-plaster traditions of Venice and refined across two decades of private and commercial commissions, our team has quietly built a reputation for finishes of unusual depth and consistency.',
            'story_body_2' => 'We work closely with leading interior designers and architects, and have been entrusted with environments for film, television and editorial productions where the surface itself must perform under the lens.',
            'story_body_3' => 'Every project begins with the room - its light, proportions, and intent - and ends with a finish made by hand.',
            'image_main' => '',
            'image_accent' => '',
            'image_studio' => '',
            'image_main_alt' => 'Bespoke interior finish',
            'image_accent_alt' => 'Signature polished finish',
            'image_studio_alt' => 'Workshop and studio finish development',
            'stat1_num' => '20+',
            'stat1_label' => 'Years of practice',
            'stat2_num' => '300+',
            'stat2_label' => 'Private commissions',
            'stat3_num' => '40+',
            'stat3_label' => 'Productions worked on',
            'workshop_eyebrow' => 'Workshop & studio',
            'workshop_heading' => 'Where the work begins.',
            'workshop_body' => 'Samples, mock-ups and bespoke profiles are developed at our London studio before being installed on site by our master artisans.',
            'workshop_btn_text' => 'Visit the studio',
            'workshop_btn_url' => '',
        ];
        $data = [];
        foreach ($defaults as $key => $default) {
            $data[$key] = array_key_exists($key, $stored) ? $stored[$key] : $default;
        }

        return view('admin.theme-options.about-page', compact('data'));
    }

    public function updateAboutPage(UpdateAboutPageContentRequest $request)
    {
        $content = AboutPageContent::query()->firstOrCreate(
            ['page_key' => AboutPageContent::PAGE_KEY_LISTING],
            ['data' => []]
        );
        $sectionData = is_array($content->data) ? $content->data : [];
        $validated = $request->validated();

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

        return back()->with('success', 'About page saved.');
    }

    public function contactPage()
    {
        ContactPageContent::query()->firstOrCreate(
            ['page_key' => ContactPageContent::PAGE_KEY],
            ['data' => []]
        );
        $data = ContactPageContent::viewDataWithDefaults();

        return view('admin.theme-options.contact-page', compact('data'));
    }

    public function updateContactPage(UpdateContactPageContentRequest $request)
    {
        $content = ContactPageContent::query()->firstOrCreate(
            ['page_key' => ContactPageContent::PAGE_KEY],
            ['data' => []]
        );
        $sectionData = is_array($content->data) ? $content->data : [];
        $validated = $request->validated();

        foreach ([
            'page_title', 'hero_line_1', 'hero_line_2', 'hero_cta',
            'info_eyebrow', 'info_heading_1', 'info_heading_2', 'info_lead',
            'studio_label', 'studio_body', 'hours_label', 'hours_body', 'appointment_line',
            'fallback_phone_display', 'fallback_whatsapp_label',
            'form_title', 'form_error_intro', 'subject_default',
            'name_placeholder', 'email_placeholder', 'phone_field_label', 'national_placeholder',
            'message_placeholder', 'submit_label', 'map_embed_url',
        ] as $field) {
            if (array_key_exists($field, $validated)) {
                $sectionData[$field] = $validated[$field];
            }
        }
        $sectionData['show_map'] = $request->boolean('show_map');

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

        return back()->with('success', 'Contact page saved.');
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
        $sectionData['booking_text'] = $data['home_atelier_booking_text'] ?? null;
        $sectionData['booking_url'] = $data['home_atelier_booking_url'] ?? null;

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

        $commissions = HomePageSection::query()->firstOrCreate(['section_key' => 'commissions'], ['data' => []]);
        $commissionsData = is_array($commissions->data) ? $commissions->data : [];
        $commissionsData['is_enabled'] = $request->boolean('home_commissions_is_enabled');
        $commissionsData['eyebrow'] = $data['home_commissions_eyebrow'] ?? null;
        $commissionsData['heading_line_1'] = $data['home_commissions_heading_line_1'] ?? null;
        $commissionsData['button_text'] = $data['home_commissions_button_text'] ?? null;
        $commissionsData['button_url'] = $data['home_commissions_button_url'] ?? null;
        $commissions->update(['data' => $commissionsData]);

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
        $contactBandData['subject'] = $data['home_contact_band_subject'] ?? null;

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

        return back()->with('success', 'Homepage saved.');
    }
}
