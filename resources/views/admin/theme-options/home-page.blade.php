@extends('layouts.admin')

@section('title', 'Homepage')

@section('styles')
@include('admin.partials.theme-section-tabs-styles')
@endsection

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Homepage</h1>
        <p class="text-muted mb-0 small">Section tabs organize the form; a single save updates every section below.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'home'])

<form action="{{ route('admin.theme-options.home.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs card-header-tabs theme-section-tabs" id="homeSectionsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="atelier-tab" data-bs-toggle="tab" data-bs-target="#atelier-pane" type="button" role="tab" aria-controls="atelier-pane" aria-selected="true">
                        Atelier Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="finishes-tab" data-bs-toggle="tab" data-bs-target="#finishes-pane" type="button" role="tab" aria-controls="finishes-pane" aria-selected="false">
                        Finishes Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services-pane" type="button" role="tab" aria-controls="services-pane" aria-selected="false">
                        Services Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="commissions-tab" data-bs-toggle="tab" data-bs-target="#commissions-pane" type="button" role="tab" aria-controls="commissions-pane" aria-selected="false">
                        Selected Work
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="why-tab" data-bs-toggle="tab" data-bs-target="#why-pane" type="button" role="tab" aria-controls="why-pane" aria-selected="false">
                        Why Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="process-tab" data-bs-toggle="tab" data-bs-target="#process-pane" type="button" role="tab" aria-controls="process-pane" aria-selected="false">
                        Process Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="begin-cta-tab" data-bs-toggle="tab" data-bs-target="#begin-cta-pane" type="button" role="tab" aria-controls="begin-cta-pane" aria-selected="false">
                        Begin CTA Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-band-tab" data-bs-toggle="tab" data-bs-target="#contact-band-pane" type="button" role="tab" aria-controls="contact-band-pane" aria-selected="false">
                        Contact Band Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="brands-strip-tab" data-bs-toggle="tab" data-bs-target="#brands-strip-pane" type="button" role="tab" aria-controls="brands-strip-pane" aria-selected="false">
                        Brands Strip Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="blog-preview-tab" data-bs-toggle="tab" data-bs-target="#blog-preview-pane" type="button" role="tab" aria-controls="blog-preview-pane" aria-selected="false">
                        Blog Preview Section
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content theme-section-tabs__panels" id="homeSectionsTabsContent">
                <div class="tab-pane fade show active" id="atelier-pane" role="tabpanel" aria-labelledby="atelier-tab" tabindex="0">
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
                            <label class="form-label">Kicker</label>
                            <input type="text" name="home_atelier_kicker" class="form-control"
                                   value="{{ old('home_atelier_kicker', $atelierSection['kicker'] ?? '') }}"
                                   placeholder="The Atelier">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Get In Touch Button Text</label>
                            <input type="text" name="home_atelier_cta_text" class="form-control"
                                   value="{{ old('home_atelier_cta_text', $atelierSection['cta_text'] ?? '') }}"
                                   placeholder="Get in touch">
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
                                   placeholder="https://example.com or /contact">
                            <div class="form-text">Optional. If left empty, button will open the Contact page.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Booking Label</label>
                            <input type="text" name="home_atelier_booking_label" class="form-control"
                                   value="{{ old('home_atelier_booking_label', $atelierSection['booking_label'] ?? '') }}"
                                   placeholder="Booking Now">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Booking Text</label>
                            <input type="text" name="home_atelier_booking_text" class="form-control"
                                   value="{{ old('home_atelier_booking_text', $atelierSection['booking_text'] ?? '') }}"
                                   placeholder="+44 20 7946 0958">
                            <div class="form-text">This text is shown on frontend (phone number or any text).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Booking URL</label>
                            <input type="text" name="home_atelier_booking_url" class="form-control"
                                   value="{{ old('home_atelier_booking_url', $atelierSection['booking_url'] ?? '') }}"
                                   placeholder="tel:+442079460958 or https://wa.me/...">
                            <div class="form-text">If this is a phone number like +44..., it auto-converts to a clickable tel link.</div>
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

                <div class="tab-pane fade" id="finishes-pane" role="tabpanel" aria-labelledby="finishes-tab" tabindex="0">
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
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_finishes_eyebrow" class="form-control"
                                   value="{{ old('home_finishes_eyebrow', $finishesSection['eyebrow'] ?? '') }}"
                                   placeholder="The Finishes">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 1</label>
                            <input type="text" name="home_finishes_heading_line_1" class="form-control"
                                   value="{{ old('home_finishes_heading_line_1', $finishesSection['heading_line_1'] ?? '') }}"
                                   placeholder="Six surfaces,">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 2</label>
                            <input type="text" name="home_finishes_heading_line_2" class="form-control"
                                   value="{{ old('home_finishes_heading_line_2', $finishesSection['heading_line_2'] ?? '') }}"
                                   placeholder="infinite tones.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card Label</label>
                            <input type="text" name="home_finishes_card_label" class="form-control"
                                   value="{{ old('home_finishes_card_label', $finishesSection['card_label'] ?? '') }}"
                                   placeholder="Finish">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="home_finishes_button_text" class="form-control"
                                   value="{{ old('home_finishes_button_text', $finishesSection['button_text'] ?? '') }}"
                                   placeholder="All finishes">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="text" name="home_finishes_button_url" class="form-control"
                                   value="{{ old('home_finishes_button_url', $finishesSection['button_url'] ?? '') }}"
                                   placeholder="{{ route('finishes') }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="services-pane" role="tabpanel" aria-labelledby="services-tab" tabindex="0">
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
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_services_eyebrow" class="form-control"
                                   value="{{ old('home_services_eyebrow', $servicesSection['eyebrow'] ?? '') }}"
                                   placeholder="Our Services">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 1</label>
                            <input type="text" name="home_services_heading_line_1" class="form-control"
                                   value="{{ old('home_services_heading_line_1', $servicesSection['heading_line_1'] ?? '') }}"
                                   placeholder="Three disciplines,">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 2</label>
                            <input type="text" name="home_services_heading_line_2" class="form-control"
                                   value="{{ old('home_services_heading_line_2', $servicesSection['heading_line_2'] ?? '') }}"
                                   placeholder="one obsession.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Card Link Text</label>
                            <input type="text" name="home_services_card_link_text" class="form-control"
                                   value="{{ old('home_services_card_link_text', $servicesSection['card_link_text'] ?? '') }}"
                                   placeholder="Discover">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="home_services_button_text" class="form-control"
                                   value="{{ old('home_services_button_text', $servicesSection['button_text'] ?? '') }}"
                                   placeholder="See all services">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Button URL</label>
                            <input type="text" name="home_services_button_url" class="form-control"
                                   value="{{ old('home_services_button_url', $servicesSection['button_url'] ?? '') }}"
                                   placeholder="{{ route('services') }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="commissions-pane" role="tabpanel" aria-labelledby="commissions-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_commissions_is_enabled" value="1" id="home_commissions_is_enabled"
                                   {{ old('home_commissions_is_enabled', array_key_exists('is_enabled', $commissionsSection ?? []) ? !empty($commissionsSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_commissions_is_enabled">Show Selected Work Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            Control visibility of the selected work/commissions section. Gallery cards still come from Gallery module.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_commissions_eyebrow" class="form-control"
                                   value="{{ old('home_commissions_eyebrow', $commissionsSection['eyebrow'] ?? '') }}"
                                   placeholder="Selected Work">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading</label>
                            <input type="text" name="home_commissions_heading_line_1" class="form-control"
                                   value="{{ old('home_commissions_heading_line_1', $commissionsSection['heading_line_1'] ?? '') }}"
                                   placeholder="Recent commissions.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="home_commissions_button_text" class="form-control"
                                   value="{{ old('home_commissions_button_text', $commissionsSection['button_text'] ?? '') }}"
                                   placeholder="View full gallery">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="text" name="home_commissions_button_url" class="form-control"
                                   value="{{ old('home_commissions_button_url', $commissionsSection['button_url'] ?? '') }}"
                                   placeholder="{{ route('gallery') }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="why-pane" role="tabpanel" aria-labelledby="why-tab" tabindex="0">
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
                        <div class="col-md-4">
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_why_eyebrow" class="form-control"
                                   value="{{ old('home_why_eyebrow', $whySection['eyebrow'] ?? '') }}"
                                   placeholder="Why Bespoke Ornate">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading</label>
                            <input type="text" name="home_why_heading" class="form-control"
                                   value="{{ old('home_why_heading', $whySection['heading'] ?? '') }}"
                                   placeholder="A studio defined by its hands.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lead Text</label>
                            <input type="text" name="home_why_lead" class="form-control"
                                   value="{{ old('home_why_lead', $whySection['lead'] ?? '') }}"
                                   placeholder="Each project is led by master artisans...">
                        </div>
                    </div>

                    @php
                        $whyCards = is_array($whySection['cards'] ?? null) ? $whySection['cards'] : [];
                    @endphp
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label fw-semibold mb-0">Cards</label></div>

                        <div class="col-md-4">
                            <label class="form-label">Card 1 Icon</label>
                            <input type="text" name="home_why_card_1_icon" class="form-control" value="{{ old('home_why_card_1_icon', $whyCards[0]['icon'] ?? 'fa-award') }}" placeholder="fa-award">
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
                            <input type="text" name="home_why_card_2_icon" class="form-control" value="{{ old('home_why_card_2_icon', $whyCards[1]['icon'] ?? 'fa-palette') }}" placeholder="fa-palette">
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
                            <input type="text" name="home_why_card_3_icon" class="form-control" value="{{ old('home_why_card_3_icon', $whyCards[2]['icon'] ?? 'fa-clapperboard') }}" placeholder="fa-clapperboard">
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
                            <input type="text" name="home_why_card_4_icon" class="form-control" value="{{ old('home_why_card_4_icon', $whyCards[3]['icon'] ?? 'fa-leaf') }}" placeholder="fa-leaf">
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

                <div class="tab-pane fade" id="process-pane" role="tabpanel" aria-labelledby="process-tab" tabindex="0">
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
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_process_eyebrow" class="form-control"
                                   value="{{ old('home_process_eyebrow', $processSection['eyebrow'] ?? '') }}"
                                   placeholder="Our Process">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 1</label>
                            <input type="text" name="home_process_heading_line_1" class="form-control"
                                   value="{{ old('home_process_heading_line_1', $processSection['heading_line_1'] ?? '') }}"
                                   placeholder="From first conversation">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading Line 2</label>
                            <input type="text" name="home_process_heading_line_2" class="form-control"
                                   value="{{ old('home_process_heading_line_2', $processSection['heading_line_2'] ?? '') }}"
                                   placeholder="to final polish.">
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

                <div class="tab-pane fade" id="begin-cta-pane" role="tabpanel" aria-labelledby="begin-cta-tab" tabindex="0">
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
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_begin_cta_eyebrow" class="form-control"
                                   value="{{ old('home_begin_cta_eyebrow', $beginCtaSection['eyebrow'] ?? '') }}"
                                   placeholder="Begin a Project">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 1</label>
                            <input type="text" name="home_begin_cta_title_line_1" class="form-control"
                                   value="{{ old('home_begin_cta_title_line_1', $beginCtaSection['title_line_1'] ?? '') }}"
                                   placeholder="Transform your space">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 2</label>
                            <input type="text" name="home_begin_cta_title_line_2" class="form-control"
                                   value="{{ old('home_begin_cta_title_line_2', $beginCtaSection['title_line_2'] ?? '') }}"
                                   placeholder="into a quiet masterpiece.">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Primary Button Text</label>
                            <input type="text" name="home_begin_cta_primary_btn_text" class="form-control"
                                   value="{{ old('home_begin_cta_primary_btn_text', $beginCtaSection['primary_btn_text'] ?? '') }}"
                                   placeholder="Get free consultation">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Primary Button URL</label>
                            <input type="text" name="home_begin_cta_primary_btn_url" class="form-control"
                                   value="{{ old('home_begin_cta_primary_btn_url', $beginCtaSection['primary_btn_url'] ?? '') }}"
                                   placeholder="{{ route('contact') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Secondary Button Text</label>
                            <input type="text" name="home_begin_cta_secondary_btn_text" class="form-control"
                                   value="{{ old('home_begin_cta_secondary_btn_text', $beginCtaSection['secondary_btn_text'] ?? '') }}"
                                   placeholder="Call the studio">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Secondary Button URL</label>
                            <input type="text" name="home_begin_cta_secondary_btn_url" class="form-control"
                                   value="{{ old('home_begin_cta_secondary_btn_url', $beginCtaSection['secondary_btn_url'] ?? '') }}"
                                   placeholder="tel:+442079460958">
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

                <div class="tab-pane fade" id="contact-band-pane" role="tabpanel" aria-labelledby="contact-band-tab" tabindex="0">
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
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_contact_band_eyebrow" class="form-control"
                                   value="{{ old('home_contact_band_eyebrow', $contactBandSection['eyebrow'] ?? '') }}"
                                   placeholder="Contact Us">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading</label>
                            <input type="text" name="home_contact_band_heading" class="form-control"
                                   value="{{ old('home_contact_band_heading', $contactBandSection['heading'] ?? '') }}"
                                   placeholder="How we can help?">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Panel Title</label>
                            <input type="text" name="home_contact_band_panel_title" class="form-control"
                                   value="{{ old('home_contact_band_panel_title', $contactBandSection['panel_title'] ?? '') }}"
                                   placeholder="Contact Us">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Name Placeholder</label>
                            <input type="text" name="home_contact_band_name_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_name_placeholder', $contactBandSection['name_placeholder'] ?? '') }}"
                                   placeholder="Your Name">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email Placeholder</label>
                            <input type="text" name="home_contact_band_email_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_email_placeholder', $contactBandSection['email_placeholder'] ?? '') }}"
                                   placeholder="Email">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Phone Placeholder</label>
                            <input type="text" name="home_contact_band_phone_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_phone_placeholder', $contactBandSection['phone_placeholder'] ?? '') }}"
                                   placeholder="Phone(Optional)">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Message Placeholder</label>
                            <input type="text" name="home_contact_band_message_placeholder" class="form-control"
                                   value="{{ old('home_contact_band_message_placeholder', $contactBandSection['message_placeholder'] ?? '') }}"
                                   placeholder="Tell us about your space">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Submit Button Text</label>
                            <input type="text" name="home_contact_band_submit_text" class="form-control"
                                   value="{{ old('home_contact_band_submit_text', $contactBandSection['submit_text'] ?? '') }}"
                                   placeholder="Send Enquiry">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Form Subject (Hidden)</label>
                            <input type="text" name="home_contact_band_subject" class="form-control"
                                   value="{{ old('home_contact_band_subject', $contactBandSection['subject'] ?? '') }}"
                                   placeholder="Website enquiry (home)">
                        </div>

                        <div class="col-md-6">
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

                <div class="tab-pane fade" id="brands-strip-pane" role="tabpanel" aria-labelledby="brands-strip-tab" tabindex="0">
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
                            <label class="form-label">Kicker</label>
                            <input type="text" name="home_brands_strip_kicker" class="form-control"
                                   value="{{ old('home_brands_strip_kicker', $brandsStripSection['kicker'] ?? '') }}"
                                   placeholder="Partners & collaborators">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 1</label>
                            <input type="text" name="home_brands_strip_title_line_1" class="form-control"
                                   value="{{ old('home_brands_strip_title_line_1', $brandsStripSection['title_line_1'] ?? '') }}"
                                   placeholder="Trusted by">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Title Line 2</label>
                            <input type="text" name="home_brands_strip_title_line_2" class="form-control"
                                   value="{{ old('home_brands_strip_title_line_2', $brandsStripSection['title_line_2'] ?? '') }}"
                                   placeholder="leading names">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marquee Segments</label>
                            <input type="number" min="1" max="20" name="home_brands_strip_marquee_segments" class="form-control"
                                   value="{{ old('home_brands_strip_marquee_segments', $brandsStripSection['marquee_segments'] ?? 8) }}">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="blog-preview-pane" role="tabpanel" aria-labelledby="blog-preview-tab" tabindex="0">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="home_blog_preview_is_enabled" value="1" id="home_blog_preview_is_enabled"
                                   {{ old('home_blog_preview_is_enabled', array_key_exists('is_enabled', $blogPreviewSection ?? []) ? !empty($blogPreviewSection['is_enabled']) : true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="home_blog_preview_is_enabled">Show Blog Preview Section on Home Page</label>
                        </div>
                        <div class="form-text">
                            This controls heading/button/read-more text. Cards still come from the Blog module.
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Eyebrow</label>
                            <input type="text" name="home_blog_preview_eyebrow" class="form-control"
                                   value="{{ old('home_blog_preview_eyebrow', $blogPreviewSection['eyebrow'] ?? '') }}"
                                   placeholder="Our Blog">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Heading</label>
                            <input type="text" name="home_blog_preview_heading" class="form-control"
                                   value="{{ old('home_blog_preview_heading', $blogPreviewSection['heading'] ?? '') }}"
                                   placeholder="Latest News">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Read More Text</label>
                            <input type="text" name="home_blog_preview_read_more_text" class="form-control"
                                   value="{{ old('home_blog_preview_read_more_text', $blogPreviewSection['read_more_text'] ?? '') }}"
                                   placeholder="Read More">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button Text</label>
                            <input type="text" name="home_blog_preview_button_text" class="form-control"
                                   value="{{ old('home_blog_preview_button_text', $blogPreviewSection['button_text'] ?? '') }}"
                                   placeholder="All Blogs">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Button URL</label>
                            <input type="text" name="home_blog_preview_button_url" class="form-control"
                                   value="{{ old('home_blog_preview_button_url', $blogPreviewSection['button_url'] ?? '') }}"
                                   placeholder="{{ route('blog.index') }}">
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
