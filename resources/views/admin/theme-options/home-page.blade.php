@extends('layouts.admin')

@section('title', 'Home Page')

@section('styles')
@include('admin.partials.theme-section-tabs-styles')
@endsection

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Home Page</h1>
        <p class="text-muted mb-0 small">Section tabs organize the form; a single save updates every section below.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'home'])

@php
    $activeHomeSection = \App\Support\HomePageAdminTabs::normalize(old('home_active_section', $homeActiveSection ?? null));
    $homeSectionTabDefaults = [
        'atelier' => 'Atelier Section',
        'finishes' => 'Finishes Section',
        'services' => 'Services Section',
        'why' => 'Why Section',
        'process' => 'Process Section',
        'testimonials' => 'Testimonials Section',
        'commissions' => 'Selected work',
        'begin-cta' => 'Begin CTA Section',
        'contact-band' => 'Contact Band Section',
        'brands-strip' => 'Brands Strip Section',
        'blog-preview' => 'Blog Preview Section',
    ];
    $homeSectionTabTitle = function (string $key) use ($homeSectionTabDefaults, $atelierSection, $finishesSection, $servicesSection, $whySection, $processSection, $testimonialsSection, $commissionsSection, $beginCtaSection, $contactBandSection, $brandsStripSection, $blogPreviewSection): string {
        $raw = match ($key) {
            'atelier' => old('home_atelier_kicker', $atelierSection['kicker'] ?? ''),
            'finishes' => old('home_finishes_eyebrow', $finishesSection['eyebrow'] ?? ''),
            'services' => old('home_services_eyebrow', $servicesSection['eyebrow'] ?? ''),
            'why' => old('home_why_eyebrow', $whySection['eyebrow'] ?? ''),
            'process' => old('home_process_eyebrow', $processSection['eyebrow'] ?? ''),
            'testimonials' => old('home_testimonials_left_eyebrow', $testimonialsSection['left_eyebrow'] ?? ''),
            'commissions' => old('home_commissions_eyebrow', $commissionsSection['eyebrow'] ?? ''),
            'begin-cta' => old('home_begin_cta_eyebrow', $beginCtaSection['eyebrow'] ?? ''),
            'contact-band' => old('home_contact_band_eyebrow', $contactBandSection['eyebrow'] ?? ''),
            'brands-strip' => old('home_brands_strip_kicker', $brandsStripSection['kicker'] ?? ''),
            'blog-preview' => old('home_blog_preview_eyebrow', $blogPreviewSection['eyebrow'] ?? ''),
            default => '',
        };
        $t = trim((string) $raw);

        return $t !== '' ? $t : ($homeSectionTabDefaults[$key] ?? $key);
    };
@endphp

<form action="{{ route('admin.theme-options.home.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="home_active_section" id="home_active_section" value="{{ $activeHomeSection }}">

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs card-header-tabs theme-section-tabs" id="homeSectionsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'atelier') active @endif" id="atelier-tab" data-bs-toggle="tab" data-bs-target="#atelier-pane" aria-controls="atelier-pane" data-theme-section="atelier" data-home-tab-default="{{ $homeSectionTabDefaults['atelier'] }}" aria-selected="{{ $activeHomeSection === 'atelier' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('atelier') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'finishes') active @endif" id="finishes-tab" data-bs-toggle="tab" data-bs-target="#finishes-pane" aria-controls="finishes-pane" data-theme-section="finishes" data-home-tab-default="{{ $homeSectionTabDefaults['finishes'] }}" aria-selected="{{ $activeHomeSection === 'finishes' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('finishes') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'services') active @endif" id="services-tab" data-bs-toggle="tab" data-bs-target="#services-pane" aria-controls="services-pane" data-theme-section="services" data-home-tab-default="{{ $homeSectionTabDefaults['services'] }}" aria-selected="{{ $activeHomeSection === 'services' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('services') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'why') active @endif" id="why-tab" data-bs-toggle="tab" data-bs-target="#why-pane" aria-controls="why-pane" data-theme-section="why" data-home-tab-default="{{ $homeSectionTabDefaults['why'] }}" aria-selected="{{ $activeHomeSection === 'why' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('why') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'process') active @endif" id="process-tab" data-bs-toggle="tab" data-bs-target="#process-pane" aria-controls="process-pane" data-theme-section="process" data-home-tab-default="{{ $homeSectionTabDefaults['process'] }}" aria-selected="{{ $activeHomeSection === 'process' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('process') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'testimonials') active @endif" id="testimonials-tab" data-bs-toggle="tab" data-bs-target="#testimonials-pane" aria-controls="testimonials-pane" data-theme-section="testimonials" data-home-tab-default="{{ $homeSectionTabDefaults['testimonials'] }}" aria-selected="{{ $activeHomeSection === 'testimonials' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('testimonials') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'commissions') active @endif" id="commissions-tab" data-bs-toggle="tab" data-bs-target="#commissions-pane" aria-controls="commissions-pane" data-theme-section="commissions" data-home-tab-default="{{ $homeSectionTabDefaults['commissions'] }}" aria-selected="{{ $activeHomeSection === 'commissions' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('commissions') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'begin-cta') active @endif" id="begin-cta-tab" data-bs-toggle="tab" data-bs-target="#begin-cta-pane" aria-controls="begin-cta-pane" data-theme-section="begin-cta" data-home-tab-default="{{ $homeSectionTabDefaults['begin-cta'] }}" aria-selected="{{ $activeHomeSection === 'begin-cta' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('begin-cta') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'contact-band') active @endif" id="contact-band-tab" data-bs-toggle="tab" data-bs-target="#contact-band-pane" aria-controls="contact-band-pane" data-theme-section="contact-band" data-home-tab-default="{{ $homeSectionTabDefaults['contact-band'] }}" aria-selected="{{ $activeHomeSection === 'contact-band' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('contact-band') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'brands-strip') active @endif" id="brands-strip-tab" data-bs-toggle="tab" data-bs-target="#brands-strip-pane" aria-controls="brands-strip-pane" data-theme-section="brands-strip" data-home-tab-default="{{ $homeSectionTabDefaults['brands-strip'] }}" aria-selected="{{ $activeHomeSection === 'brands-strip' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('brands-strip') }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeHomeSection === 'blog-preview') active @endif" id="blog-preview-tab" data-bs-toggle="tab" data-bs-target="#blog-preview-pane" aria-controls="blog-preview-pane" data-theme-section="blog-preview" data-home-tab-default="{{ $homeSectionTabDefaults['blog-preview'] }}" aria-selected="{{ $activeHomeSection === 'blog-preview' ? 'true' : 'false' }}">
                        <span class="js-home-section-tab-label">{{ $homeSectionTabTitle('blog-preview') }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content theme-section-tabs__panels" id="homeSectionsTabsContent">
                <div class="tab-pane fade @if($activeHomeSection === 'atelier') show active @endif" id="atelier-pane" role="tabpanel" aria-labelledby="atelier-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_atelier_is_enabled" value="1" id="home_atelier_is_enabled"
                                   {{ old('home_atelier_is_enabled', !empty($atelierSection['is_enabled'])) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_atelier_is_enabled">Show Atelier Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            Use this checkbox to quickly show/hide the entire Atelier section. If this is OFF, the section will not appear on frontend even if data is filled.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="home_atelier_kicker">Title</label>
                            <input type="text" name="home_atelier_kicker" id="home_atelier_kicker" class="form-control"
                                   data-sync-home-section-tab="atelier"
                                   value="{{ old('home_atelier_kicker', $atelierSection['kicker'] ?? '') }}"
                                   placeholder="e.g. The Atelier">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Get In Touch Button Text</label>
                            <input type="text" name="home_atelier_cta_text" class="form-control"
                                   value="{{ old('home_atelier_cta_text', $atelierSection['cta_text'] ?? '') }}"
                                   placeholder="e.g. Get in touch">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Heading Line 1</label>
                            <input type="text" name="home_atelier_heading_line_1" class="form-control"
                                   value="{{ old('home_atelier_heading_line_1', $atelierSection['heading_line_1'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 2</label>
                            <input type="text" name="home_atelier_heading_line_2" class="form-control"
                                   value="{{ old('home_atelier_heading_line_2', $atelierSection['heading_line_2'] ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 3</label>
                            <input type="text" name="home_atelier_heading_line_3" class="form-control"
                                   value="{{ old('home_atelier_heading_line_3', $atelierSection['heading_line_3'] ?? '') }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Body Text</label>
                            <textarea name="home_atelier_body" class="form-control" rows="4">{{ old('home_atelier_body', $atelierSection['body'] ?? '') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Get In Touch Button URL</label>
                            <input type="text" name="home_atelier_cta_url" class="form-control"
                                   value="{{ old('home_atelier_cta_url', $atelierSection['cta_url'] ?? '') }}"
                                   placeholder="e.g. https://example.com or /contact">
                            <div class="form-text">Optional. If left empty, button will open the Contact page.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Booking Label</label>
                            <input type="text" name="home_atelier_booking_label" class="form-control"
                                   value="{{ old('home_atelier_booking_label', $atelierSection['booking_label'] ?? '') }}"
                                   placeholder="e.g. Booking Now">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Booking URL or phone</label>
                            <input type="text" name="home_atelier_booking_url" class="form-control"
                                   value="{{ old('home_atelier_booking_url', $atelierSection['booking_url'] ?? '') }}"
                                   placeholder="e.g. tel:+44… or https://wa.me/…">
                            <div class="form-text">Shown as the clickable link on the site. Plain phone numbers become tel: links; you can also use a full URL.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Primary Image</label>
                            @if(!empty($atelierSection['primary_image']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $atelierSection['primary_image']) }}" alt="Primary" class="img-preview">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_home_atelier_primary_image" value="1" id="remove_home_atelier_primary_image">
                                    <label class="form-check-label text-danger" for="remove_home_atelier_primary_image">Remove current image</label>
                                </div>
                            @endif
                            <input type="file" name="home_atelier_primary_image" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Secondary Image</label>
                            @if(!empty($atelierSection['secondary_image']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $atelierSection['secondary_image']) }}" alt="Secondary" class="img-preview">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_home_atelier_secondary_image" value="1" id="remove_home_atelier_secondary_image">
                                    <label class="form-check-label text-danger" for="remove_home_atelier_secondary_image">Remove current image</label>
                                </div>
                            @endif
                            <input type="file" name="home_atelier_secondary_image" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'finishes') show active @endif" id="finishes-pane" role="tabpanel" aria-labelledby="finishes-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_finishes_is_enabled" value="1" id="home_finishes_is_enabled"
                                   {{ old('home_finishes_is_enabled', array_key_exists('is_enabled', $finishesSection ?? []) ? !empty($finishesSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_finishes_is_enabled">Show Finishes Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            Control visibility of the whole Finishes section. This section still uses live finishes cards from the Finishes module.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="home_finishes_eyebrow">Title</label>
                            <input type="text" name="home_finishes_eyebrow" id="home_finishes_eyebrow" class="form-control"
                                   data-sync-home-section-tab="finishes"
                                   value="{{ old('home_finishes_eyebrow', $finishesSection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. The Finishes">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 1</label>
                            <input type="text" name="home_finishes_heading_line_1" class="form-control"
                                   value="{{ old('home_finishes_heading_line_1', $finishesSection['heading_line_1'] ?? '') }}"
                                   placeholder="e.g. Six surfaces,">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 2</label>
                            <input type="text" name="home_finishes_heading_line_2" class="form-control"
                                   value="{{ old('home_finishes_heading_line_2', $finishesSection['heading_line_2'] ?? '') }}"
                                   placeholder="e.g. infinite tones.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card Label</label>
                            <input type="text" name="home_finishes_card_label" class="form-control"
                                   value="{{ old('home_finishes_card_label', $finishesSection['card_label'] ?? '') }}"
                                   placeholder="e.g. Finish">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="home_finishes_button_text" class="form-control"
                                   value="{{ old('home_finishes_button_text', $finishesSection['button_text'] ?? '') }}"
                                   placeholder="e.g. All finishes">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="text" name="home_finishes_button_url" class="form-control"
                                   value="{{ old('home_finishes_button_url', $finishesSection['button_url'] ?? '') }}"
                                   placeholder="e.g. /finishes">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'services') show active @endif" id="services-pane" role="tabpanel" aria-labelledby="services-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_services_is_enabled" value="1" id="home_services_is_enabled"
                                   {{ old('home_services_is_enabled', array_key_exists('is_enabled', $servicesSection ?? []) ? !empty($servicesSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_services_is_enabled">Show Services Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            Control visibility of the Services section. Cards still come from the Services module.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="home_services_eyebrow">Title</label>
                            <input type="text" name="home_services_eyebrow" id="home_services_eyebrow" class="form-control"
                                   data-sync-home-section-tab="services"
                                   value="{{ old('home_services_eyebrow', $servicesSection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. Our Services">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 1</label>
                            <input type="text" name="home_services_heading_line_1" class="form-control"
                                   value="{{ old('home_services_heading_line_1', $servicesSection['heading_line_1'] ?? '') }}"
                                   placeholder="e.g. Three disciplines,">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 2</label>
                            <input type="text" name="home_services_heading_line_2" class="form-control"
                                   value="{{ old('home_services_heading_line_2', $servicesSection['heading_line_2'] ?? '') }}"
                                   placeholder="e.g. one obsession.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="home_services_button_text" class="form-control"
                                   value="{{ old('home_services_button_text', $servicesSection['button_text'] ?? '') }}"
                                   placeholder="e.g. See all services">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Button URL</label>
                            <input type="text" name="home_services_button_url" class="form-control"
                                   value="{{ old('home_services_button_url', $servicesSection['button_url'] ?? '') }}"
                                   placeholder="e.g. /services">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'why') show active @endif" id="why-pane" role="tabpanel" aria-labelledby="why-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_why_is_enabled" value="1" id="home_why_is_enabled"
                                   {{ old('home_why_is_enabled', array_key_exists('is_enabled', $whySection ?? []) ? !empty($whySection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_why_is_enabled">Show Why Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            Controls the "Why Bespoke Ornate" section block and its 4 feature cards.
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" for="home_why_eyebrow">Title</label>
                            <input type="text" name="home_why_eyebrow" id="home_why_eyebrow" class="form-control"
                                   data-sync-home-section-tab="why"
                                   value="{{ old('home_why_eyebrow', $whySection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. Why choose us">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Heading</label>
                            <input type="text" name="home_why_heading" class="form-control"
                                   value="{{ old('home_why_heading', $whySection['heading'] ?? '') }}"
                                   placeholder="e.g. A studio defined by its craft.">
                        </div>
                    </div>

                    @php
                        $whyCards = is_array($whySection['cards'] ?? null) ? $whySection['cards'] : [];
                    @endphp
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold mb-0">Cards</label></div>

                        <div class="col-md-4">
                            <label class="form-label">Card 1 Icon</label>
                            <input type="text" name="home_why_card_1_icon" class="form-control" value="{{ old('home_why_card_1_icon', $whyCards[0]['icon'] ?? 'fa-award') }}" placeholder="e.g. fa-award">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 1 Title</label>
                            <input type="text" name="home_why_card_1_title" class="form-control" value="{{ old('home_why_card_1_title', $whyCards[0]['title'] ?? 'Master Craftsmanship') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 1 Description</label>
                            <input type="text" name="home_why_card_1_desc" class="form-control" value="{{ old('home_why_card_1_desc', $whyCards[0]['desc'] ?? 'Every surface mixed, applied and polished by hand.') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Card 2 Icon</label>
                            <input type="text" name="home_why_card_2_icon" class="form-control" value="{{ old('home_why_card_2_icon', $whyCards[1]['icon'] ?? 'fa-palette') }}" placeholder="e.g. fa-palette">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 2 Title</label>
                            <input type="text" name="home_why_card_2_title" class="form-control" value="{{ old('home_why_card_2_title', $whyCards[1]['title'] ?? 'Bespoke by Design') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 2 Description</label>
                            <input type="text" name="home_why_card_2_desc" class="form-control" value="{{ old('home_why_card_2_desc', $whyCards[1]['desc'] ?? 'Custom tones, textures and profiles, never off-the-shelf.') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Card 3 Icon</label>
                            <input type="text" name="home_why_card_3_icon" class="form-control" value="{{ old('home_why_card_3_icon', $whyCards[2]['icon'] ?? 'fa-clapperboard') }}" placeholder="e.g. fa-clapperboard">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 3 Title</label>
                            <input type="text" name="home_why_card_3_title" class="form-control" value="{{ old('home_why_card_3_title', $whyCards[2]['title'] ?? 'Trusted by Productions') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 3 Description</label>
                            <input type="text" name="home_why_card_3_desc" class="form-control" value="{{ old('home_why_card_3_desc', $whyCards[2]['desc'] ?? 'Selected for major film, TV and editorial productions.') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Card 4 Icon</label>
                            <input type="text" name="home_why_card_4_icon" class="form-control" value="{{ old('home_why_card_4_icon', $whyCards[3]['icon'] ?? 'fa-leaf') }}" placeholder="e.g. fa-leaf">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 4 Title</label>
                            <input type="text" name="home_why_card_4_title" class="form-control" value="{{ old('home_why_card_4_title', $whyCards[3]['title'] ?? 'Considered Materials') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card 4 Description</label>
                            <input type="text" name="home_why_card_4_desc" class="form-control" value="{{ old('home_why_card_4_desc', $whyCards[3]['desc'] ?? 'Lime-based, breathable, low-VOC formulations.') }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'process') show active @endif" id="process-pane" role="tabpanel" aria-labelledby="process-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_process_is_enabled" value="1" id="home_process_is_enabled"
                                   {{ old('home_process_is_enabled', array_key_exists('is_enabled', $processSection ?? []) ? !empty($processSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_process_is_enabled">Show Process Section on Home Page</label>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label" for="home_process_eyebrow">Title</label>
                            <input type="text" name="home_process_eyebrow" id="home_process_eyebrow" class="form-control"
                                   data-sync-home-section-tab="process"
                                   value="{{ old('home_process_eyebrow', $processSection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. Our Process">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 1</label>
                            <input type="text" name="home_process_heading_line_1" class="form-control"
                                   value="{{ old('home_process_heading_line_1', $processSection['heading_line_1'] ?? '') }}"
                                   placeholder="e.g. From first conversation">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 2</label>
                            <input type="text" name="home_process_heading_line_2" class="form-control"
                                   value="{{ old('home_process_heading_line_2', $processSection['heading_line_2'] ?? '') }}"
                                   placeholder="e.g. to final polish.">
                        </div>
                    </div>

                    @php $processSteps = is_array($processSection['steps'] ?? null) ? $processSection['steps'] : []; @endphp
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold mb-0">Steps</label></div>

                        <div class="col-md-2"><label class="form-label">Step 1 Number</label><input type="text" name="home_process_step_1_num" class="form-control" value="{{ old('home_process_step_1_num', $processSteps[0]['num'] ?? '01') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 1 Title</label><input type="text" name="home_process_step_1_title" class="form-control" value="{{ old('home_process_step_1_title', $processSteps[0]['title'] ?? 'Consultation') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 1 Description</label><input type="text" name="home_process_step_1_desc" class="form-control" value="{{ old('home_process_step_1_desc', $processSteps[0]['desc'] ?? 'We visit your space, listen and study the light.') }}"></div>

                        <div class="col-md-2"><label class="form-label">Step 2 Number</label><input type="text" name="home_process_step_2_num" class="form-control" value="{{ old('home_process_step_2_num', $processSteps[1]['num'] ?? '02') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 2 Title</label><input type="text" name="home_process_step_2_title" class="form-control" value="{{ old('home_process_step_2_title', $processSteps[1]['title'] ?? 'Design') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 2 Description</label><input type="text" name="home_process_step_2_desc" class="form-control" value="{{ old('home_process_step_2_desc', $processSteps[1]['desc'] ?? 'Bespoke samples, tones and textures developed in studio.') }}"></div>

                        <div class="col-md-2"><label class="form-label">Step 3 Number</label><input type="text" name="home_process_step_3_num" class="form-control" value="{{ old('home_process_step_3_num', $processSteps[2]['num'] ?? '03') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 3 Title</label><input type="text" name="home_process_step_3_title" class="form-control" value="{{ old('home_process_step_3_title', $processSteps[2]['title'] ?? 'Quote') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 3 Description</label><input type="text" name="home_process_step_3_desc" class="form-control" value="{{ old('home_process_step_3_desc', $processSteps[2]['desc'] ?? 'A clear, transparent proposal with timelines.') }}"></div>

                        <div class="col-md-2"><label class="form-label">Step 4 Number</label><input type="text" name="home_process_step_4_num" class="form-control" value="{{ old('home_process_step_4_num', $processSteps[3]['num'] ?? '04') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 4 Title</label><input type="text" name="home_process_step_4_title" class="form-control" value="{{ old('home_process_step_4_title', $processSteps[3]['title'] ?? 'Execution') }}"></div>
                        <div class="col-md-5"><label class="form-label">Step 4 Description</label><input type="text" name="home_process_step_4_desc" class="form-control" value="{{ old('home_process_step_4_desc', $processSteps[3]['desc'] ?? 'Hand-applied by our master artisans on site.') }}"></div>
                    </div>
                </div>

                @include('admin.theme-options.partials.home-testimonials-tab')

                <div class="tab-pane fade @if($activeHomeSection === 'commissions') show active @endif" id="commissions-pane" role="tabpanel" aria-labelledby="commissions-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_commissions_is_enabled" value="1" id="home_commissions_is_enabled"
                                   {{ old('home_commissions_is_enabled', array_key_exists('is_enabled', $commissionsSection ?? []) ? !empty($commissionsSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_commissions_is_enabled">Show selected work on Home Page</label>
                        </div>
                        <div class="form-text">
                            Grid uses active items from <a href="{{ route('admin.gallery.index') }}">Gallery</a> (up to eight). Headings and button are set here.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="home_commissions_eyebrow">Title</label>
                            <input type="text" name="home_commissions_eyebrow" id="home_commissions_eyebrow" class="form-control"
                                   data-sync-home-section-tab="commissions"
                                   value="{{ old('home_commissions_eyebrow', $commissionsSection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. Selected work">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading</label>
                            <input type="text" name="home_commissions_heading_line_1" class="form-control"
                                   value="{{ old('home_commissions_heading_line_1', $commissionsSection['heading_line_1'] ?? '') }}"
                                   placeholder="e.g. Recent commissions">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Button text</label>
                            <input type="text" name="home_commissions_button_text" class="form-control"
                                   value="{{ old('home_commissions_button_text', $commissionsSection['button_text'] ?? '') }}"
                                   placeholder="e.g. View full gallery">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="text" name="home_commissions_button_url" class="form-control"
                                   value="{{ old('home_commissions_button_url', $commissionsSection['button_url'] ?? '') }}"
                                   placeholder="e.g. /gallery">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'begin-cta') show active @endif" id="begin-cta-pane" role="tabpanel" aria-labelledby="begin-cta-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_begin_cta_is_enabled" value="1" id="home_begin_cta_is_enabled"
                                   {{ old('home_begin_cta_is_enabled', array_key_exists('is_enabled', $beginCtaSection ?? []) ? !empty($beginCtaSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_begin_cta_is_enabled">Show Begin CTA Section on Home Page</label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="home_begin_cta_eyebrow">Title</label>
                            <input type="text" name="home_begin_cta_eyebrow" id="home_begin_cta_eyebrow" class="form-control"
                                   data-sync-home-section-tab="begin-cta"
                                   value="{{ old('home_begin_cta_eyebrow', $beginCtaSection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. Begin a project">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 1</label>
                            <input type="text" name="home_begin_cta_title_line_1" class="form-control"
                                   value="{{ old('home_begin_cta_title_line_1', $beginCtaSection['title_line_1'] ?? '') }}"
                                   placeholder="e.g. Transform your space">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 2</label>
                            <input type="text" name="home_begin_cta_title_line_2" class="form-control"
                                   value="{{ old('home_begin_cta_title_line_2', $beginCtaSection['title_line_2'] ?? '') }}"
                                   placeholder="e.g. into a quiet masterpiece.">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Primary Button Text</label>
                            <input type="text" name="home_begin_cta_primary_btn_text" class="form-control"
                                   value="{{ old('home_begin_cta_primary_btn_text', $beginCtaSection['primary_btn_text'] ?? '') }}"
                                   placeholder="e.g. Get a consultation">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Primary Button URL</label>
                            <input type="text" name="home_begin_cta_primary_btn_url" class="form-control"
                                   value="{{ old('home_begin_cta_primary_btn_url', $beginCtaSection['primary_btn_url'] ?? '') }}"
                                   placeholder="e.g. /contact">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Secondary Button Text</label>
                            <input type="text" name="home_begin_cta_secondary_btn_text" class="form-control"
                                   value="{{ old('home_begin_cta_secondary_btn_text', $beginCtaSection['secondary_btn_text'] ?? '') }}"
                                   placeholder="e.g. Call the studio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Secondary Button URL</label>
                            <input type="text" name="home_begin_cta_secondary_btn_url" class="form-control"
                                   value="{{ old('home_begin_cta_secondary_btn_url', $beginCtaSection['secondary_btn_url'] ?? '') }}"
                                   placeholder="e.g. tel:+44…">
                            <div class="form-text">If empty, phone button auto-uses Site Phone from settings.</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Background Image</label>
                            @if(!empty($beginCtaSection['bg_image']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $beginCtaSection['bg_image']) }}" alt="Begin CTA Background" class="img-preview">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_home_begin_cta_bg_image" value="1" id="remove_home_begin_cta_bg_image">
                                    <label class="form-check-label text-danger" for="remove_home_begin_cta_bg_image">Remove current background image</label>
                                </div>
                            @endif
                            <input type="file" name="home_begin_cta_bg_image" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'contact-band') show active @endif" id="contact-band-pane" role="tabpanel" aria-labelledby="contact-band-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_contact_band_is_enabled" value="1" id="home_contact_band_is_enabled"
                                   {{ old('home_contact_band_is_enabled', array_key_exists('is_enabled', $contactBandSection ?? []) ? !empty($contactBandSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_contact_band_is_enabled">Show Contact Band Section on Home Page</label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="home_contact_band_eyebrow">Title</label>
                            <input type="text" name="home_contact_band_eyebrow" id="home_contact_band_eyebrow" class="form-control"
                                   data-sync-home-section-tab="contact-band"
                                   value="{{ old('home_contact_band_eyebrow', $contactBandSection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. Contact us">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading</label>
                            <input type="text" name="home_contact_band_heading" class="form-control"
                                   value="{{ old('home_contact_band_heading', $contactBandSection['heading'] ?? '') }}"
                                   placeholder="e.g. How can we help?">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Panel Title</label>
                            <input type="text" name="home_contact_band_panel_title" class="form-control"
                                   value="{{ old('home_contact_band_panel_title', $contactBandSection['panel_title'] ?? '') }}"
                                   placeholder="e.g. Contact us">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Name Placeholder</label>
                            <input type="text" name="home_contact_band_name_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_name_placeholder', $contactBandSection['name_placeholder'] ?? '') }}"
                                   placeholder="e.g. Your name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email Placeholder</label>
                            <input type="text" name="home_contact_band_email_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_email_placeholder', $contactBandSection['email_placeholder'] ?? '') }}"
                                   placeholder="e.g. Email">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone Placeholder</label>
                            <input type="text" name="home_contact_band_phone_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_phone_placeholder', $contactBandSection['phone_placeholder'] ?? '') }}"
                                   placeholder="e.g. Phone (optional)">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Message Placeholder</label>
                            <input type="text" name="home_contact_band_message_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_message_placeholder', $contactBandSection['message_placeholder'] ?? '') }}"
                                   placeholder="e.g. Tell us about your project">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Submit Button Text</label>
                            <input type="text" name="home_contact_band_submit_text" class="form-control"
                                   value="{{ old('home_contact_band_submit_text', $contactBandSection['submit_text'] ?? '') }}"
                                   placeholder="e.g. Send enquiry">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Left Visual Image</label>
                            @if(!empty($contactBandSection['visual_image']))
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $contactBandSection['visual_image']) }}" alt="Contact band visual" class="img-preview">
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="remove_home_contact_band_visual_image" value="1" id="remove_home_contact_band_visual_image">
                                    <label class="form-check-label text-danger" for="remove_home_contact_band_visual_image">Remove current visual image</label>
                                </div>
                            @endif
                            <input type="file" name="home_contact_band_visual_image" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'brands-strip') show active @endif" id="brands-strip-pane" role="tabpanel" aria-labelledby="brands-strip-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_brands_strip_is_enabled" value="1" id="home_brands_strip_is_enabled"
                                   {{ old('home_brands_strip_is_enabled', array_key_exists('is_enabled', $brandsStripSection ?? []) ? !empty($brandsStripSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_brands_strip_is_enabled">Show Brands Strip Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            This controls heading text and visibility. Logos still come from the Brands module.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="home_brands_strip_kicker">Title</label>
                            <input type="text" name="home_brands_strip_kicker" id="home_brands_strip_kicker" class="form-control"
                                   data-sync-home-section-tab="brands-strip"
                                   value="{{ old('home_brands_strip_kicker', $brandsStripSection['kicker'] ?? '') }}"
                                   placeholder="e.g. Partners & collaborators">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 1</label>
                            <input type="text" name="home_brands_strip_title_line_1" class="form-control"
                                   value="{{ old('home_brands_strip_title_line_1', $brandsStripSection['title_line_1'] ?? '') }}"
                                   placeholder="e.g. Trusted by">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 2</label>
                            <input type="text" name="home_brands_strip_title_line_2" class="form-control"
                                   value="{{ old('home_brands_strip_title_line_2', $brandsStripSection['title_line_2'] ?? '') }}"
                                   placeholder="e.g. leading names">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marquee Segments</label>
                            <input type="number" min="1" max="20" name="home_brands_strip_marquee_segments" class="form-control"
                                   value="{{ old('home_brands_strip_marquee_segments', $brandsStripSection['marquee_segments'] ?? 8) }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeHomeSection === 'blog-preview') show active @endif" id="blog-preview-pane" role="tabpanel" aria-labelledby="blog-preview-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_blog_preview_is_enabled" value="1" id="home_blog_preview_is_enabled"
                                   {{ old('home_blog_preview_is_enabled', array_key_exists('is_enabled', $blogPreviewSection ?? []) ? !empty($blogPreviewSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_blog_preview_is_enabled">Show Blog Preview Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            Section title, heading, and button label. Blog cards are managed in the Blog module.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="home_blog_preview_eyebrow">Title</label>
                            <input type="text" name="home_blog_preview_eyebrow" id="home_blog_preview_eyebrow" class="form-control"
                                   data-sync-home-section-tab="blog-preview"
                                   value="{{ old('home_blog_preview_eyebrow', $blogPreviewSection['eyebrow'] ?? '') }}"
                                   placeholder="e.g. Our Blog">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="home_blog_preview_heading">Heading</label>
                            <input type="text" name="home_blog_preview_heading" id="home_blog_preview_heading" class="form-control"
                                   value="{{ old('home_blog_preview_heading', $blogPreviewSection['heading'] ?? '') }}"
                                   placeholder="e.g. Latest News">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="home_blog_preview_button_text">Button Text</label>
                            <input type="text" name="home_blog_preview_button_text" id="home_blog_preview_button_text" class="form-control"
                                   value="{{ old('home_blog_preview_button_text', $blogPreviewSection['button_text'] ?? '') }}"
                                   placeholder="e.g. All Blogs">
                            <div class="form-text">Links to the blog page automatically. Leave empty to use &ldquo;All Blogs&rdquo;.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-2"></i>Save Home Page
    </button>
</form>
@endsection

@section('scripts')
@include('admin.partials.theme-section-tab-persist-script', ['tabListId' => 'homeSectionsTabs', 'inputId' => 'home_active_section'])
@include('admin.partials.home-section-tab-title-sync-script', ['tabListId' => 'homeSectionsTabs'])
@endsection
