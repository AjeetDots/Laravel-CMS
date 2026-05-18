@extends('layouts.admin')

@section('title', 'Contact page')

@section('styles')
@include('admin.partials.theme-section-tabs-styles')
@endsection

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Contact page</h1>
        <p class="text-muted mb-0 small">Public page at <code>/contact</code>. Phone, email and WhatsApp links still use <a href="{{ route('admin.settings.index') }}">Site settings</a> where configured.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'contact'])

@php
    $activeContentSection = \App\Support\ThemeContentPageTabs::normalizeIn(
        \App\Support\ThemeContentPageTabs::CONTACT,
        'hero',
        old('contact_page_active_section', $activeContentSection)
    );
@endphp

<form action="{{ route('admin.theme-options.contact.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="contact_page_active_section" id="contact_page_active_section" value="{{ $activeContentSection }}">

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs card-header-tabs theme-section-tabs" id="contactPageSectionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'hero') active @endif" id="contact-hero-tab" data-bs-toggle="tab" data-bs-target="#contact-hero-pane" aria-controls="contact-hero-pane" data-theme-section="hero" aria-selected="{{ $activeContentSection === 'hero' ? 'true' : 'false' }}">Hero</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'info') active @endif" id="contact-info-tab" data-bs-toggle="tab" data-bs-target="#contact-info-pane" aria-controls="contact-info-pane" data-theme-section="info" aria-selected="{{ $activeContentSection === 'info' ? 'true' : 'false' }}">Info column</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'form') active @endif" id="contact-form-tab" data-bs-toggle="tab" data-bs-target="#contact-form-pane" aria-controls="contact-form-pane" data-theme-section="form" aria-selected="{{ $activeContentSection === 'form' ? 'true' : 'false' }}">Form</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'map') active @endif" id="contact-map-tab" data-bs-toggle="tab" data-bs-target="#contact-map-pane" aria-controls="contact-map-pane" data-theme-section="map" aria-selected="{{ $activeContentSection === 'map' ? 'true' : 'false' }}">Map</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content theme-section-tabs__panels" id="contactPageTabsContent">
                <div class="tab-pane fade @if($activeContentSection === 'hero') show active @endif" id="contact-hero-pane" role="tabpanel" aria-labelledby="contact-hero-tab" tabindex="0">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="hero_cta">Hero button label</label>
                            <input type="text" name="hero_cta" id="hero_cta" class="form-control" value="{{ old('hero_cta', $data['hero_cta'] ?? '') }}" maxlength="120" placeholder="e.g. Get a quote">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="hero_line_1">Hero heading line 1</label>
                            <input type="text" name="hero_line_1" id="hero_line_1" class="form-control" value="{{ old('hero_line_1', $data['hero_line_1'] ?? '') }}" maxlength="255" placeholder="e.g. Bring us your space.">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="hero_line_2">Hero heading line 2</label>
                            <input type="text" name="hero_line_2" id="hero_line_2" class="form-control" value="{{ old('hero_line_2', $data['hero_line_2'] ?? '') }}" maxlength="255" placeholder="e.g. We’ll bring the finish.">
                        </div>
                    </div>
                    <div class="mt-3 mb-0">
                        <label class="form-label">Hero background image</label>
                        <p class="text-muted small">Optional. If removed and not replaced, the first active <a href="{{ route('admin.gallery.index') }}">Gallery</a> image is used.</p>
                        @if(!empty($data['hero_bg_image'] ?? null))
                            <div class="mb-2"><img src="{{ asset('storage/'.$data['hero_bg_image']) }}" alt="" class="img-preview" style="max-height:120px;border-radius:8px;"></div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="remove_contact_hero_bg_image" id="remove_contact_hero_bg_image" value="1" {{ old('remove_contact_hero_bg_image') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remove_contact_hero_bg_image">Remove current image</label>
                            </div>
                        @endif
                        <input type="file" name="contact_hero_bg_image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'info') show active @endif" id="contact-info-pane" role="tabpanel" aria-labelledby="contact-info-tab" tabindex="0">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="info_eyebrow">Eyebrow</label>
                            <input type="text" name="info_eyebrow" id="info_eyebrow" class="form-control" value="{{ old('info_eyebrow', $data['info_eyebrow'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="info_heading_1">Heading line 1</label>
                            <input type="text" name="info_heading_1" id="info_heading_1" class="form-control" value="{{ old('info_heading_1', $data['info_heading_1'] ?? '') }}" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="info_heading_2">Heading line 2</label>
                            <input type="text" name="info_heading_2" id="info_heading_2" class="form-control" value="{{ old('info_heading_2', $data['info_heading_2'] ?? '') }}" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="appointment_line">Clock line (e.g. by appointment)</label>
                            <input type="text" name="appointment_line" id="appointment_line" class="form-control" value="{{ old('appointment_line', $data['appointment_line'] ?? '') }}" maxlength="255">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="info_lead">Lead paragraph</label>
                            <textarea name="info_lead" id="info_lead" class="form-control" rows="4" maxlength="2000">{{ old('info_lead', $data['info_lead'] ?? '') }}</textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="studio_label">Studio label</label>
                            <input type="text" name="studio_label" id="studio_label" class="form-control" value="{{ old('studio_label', $data['studio_label'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="hours_label">Hours label</label>
                            <input type="text" name="hours_label" id="hours_label" class="form-control" value="{{ old('hours_label', $data['hours_label'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="studio_body">Studio address <span class="text-muted fw-normal">(line breaks allowed)</span></label>
                            <textarea name="studio_body" id="studio_body" class="form-control" rows="3" maxlength="500">{{ old('studio_body', $data['studio_body'] ?? '') }}</textarea>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="hours_body">Hours text <span class="text-muted fw-normal">(line breaks allowed)</span></label>
                            <textarea name="hours_body" id="hours_body" class="form-control" rows="3" maxlength="500">{{ old('hours_body', $data['hours_body'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'form') show active @endif" id="contact-form-pane" role="tabpanel" aria-labelledby="contact-form-tab" tabindex="0">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="form_title">Form panel title</label>
                            <input type="text" name="form_title" id="form_title" class="form-control" value="{{ old('form_title', $data['form_title'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="form_error_intro">Validation alert text</label>
                            <input type="text" name="form_error_intro" id="form_error_intro" class="form-control" value="{{ old('form_error_intro', $data['form_error_intro'] ?? '') }}" maxlength="500">
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="name_placeholder">Name placeholder</label>
                            <input type="text" name="name_placeholder" id="name_placeholder" class="form-control" value="{{ old('name_placeholder', $data['name_placeholder'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="email_placeholder">Email placeholder</label>
                            <input type="text" name="email_placeholder" id="email_placeholder" class="form-control" value="{{ old('email_placeholder', $data['email_placeholder'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="phone_field_label">Phone field label</label>
                            <input type="text" name="phone_field_label" id="phone_field_label" class="form-control" value="{{ old('phone_field_label', $data['phone_field_label'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="national_placeholder">Phone number placeholder</label>
                            <input type="text" name="national_placeholder" id="national_placeholder" class="form-control" value="{{ old('national_placeholder', $data['national_placeholder'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="message_placeholder">Message placeholder</label>
                            <input type="text" name="message_placeholder" id="message_placeholder" class="form-control" value="{{ old('message_placeholder', $data['message_placeholder'] ?? '') }}" maxlength="255">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="submit_label">Submit button</label>
                            <input type="text" name="submit_label" id="submit_label" class="form-control" value="{{ old('submit_label', $data['submit_label'] ?? '') }}" maxlength="120">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'map') show active @endif" id="contact-map-pane" role="tabpanel" aria-labelledby="contact-map-tab" tabindex="0">
                    <div class="mb-3">
                        <input type="hidden" name="show_map" value="0">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="show_map" id="show_map" value="1"
                                   {{ (string) old('show_map', ($data['show_map'] ?? true) ? '1' : '0') === '1' ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="show_map">Show map section on contact page</label>
                        </div>
                        <div class="form-text">
                            Use this switch to quickly show or hide the map on the public contact page. If this is off, the map will not appear even if an embed URL is saved.
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="map_embed_url">Google Maps embed URL</label>
                        <textarea name="map_embed_url" id="map_embed_url" class="form-control font-monospace small @error('map_embed_url') is-invalid @enderror" rows="4" maxlength="5000" placeholder="e.g. https://www.google.com/maps/embed?…">{{ old('map_embed_url', $data['map_embed_url'] ?? '') }}</textarea>
                        @error('map_embed_url')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <span class="form-text">Paste the full <code>src</code> value from the Google Maps embed code.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save contact page</button>
</form>
@endsection

@section('scripts')
@include('admin.partials.theme-section-tab-persist-script', ['tabListId' => 'contactPageSectionTabs', 'inputId' => 'contact_page_active_section'])
@endsection
