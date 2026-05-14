@extends('layouts.admin')

@section('title', 'Services page')

@section('styles')
@include('admin.partials.theme-section-tabs-styles')
@endsection

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Services page</h1>
        <p class="text-muted mb-0 small">Public listing at <code>/services</code>. Section tabs organize the form; a single save updates every section. Service records are under <a href="{{ route('admin.services.index') }}">Services</a> in Content.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'services'])

@php
    $activeContentSection = \App\Support\ThemeContentPageTabs::normalizeIn(
        \App\Support\ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM,
        'intro',
        old('services_page_active_section', $activeContentSection)
    );
@endphp

<form action="{{ route('admin.theme-options.services.update') }}" method="POST">
    @csrf
    <input type="hidden" name="services_page_active_section" id="services_page_active_section" value="{{ $activeContentSection }}">

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs card-header-tabs theme-section-tabs" id="servicesPageSectionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'intro') active @endif" id="services-intro-tab" data-bs-toggle="tab" data-bs-target="#services-intro-pane" aria-controls="services-intro-pane" data-theme-section="intro" aria-selected="{{ $activeContentSection === 'intro' ? 'true' : 'false' }}">
                        Intro
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'grid') active @endif" id="services-grid-tab" data-bs-toggle="tab" data-bs-target="#services-grid-pane" aria-controls="services-grid-pane" data-theme-section="grid" aria-selected="{{ $activeContentSection === 'grid' ? 'true' : 'false' }}">
                        Grid
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'bottom') active @endif" id="services-bottom-tab" data-bs-toggle="tab" data-bs-target="#services-bottom-pane" aria-controls="services-bottom-pane" data-theme-section="bottom" aria-selected="{{ $activeContentSection === 'bottom' ? 'true' : 'false' }}">
                        Bottom CTA
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content theme-section-tabs__panels" id="servicesPageTabsContent">
                <div class="tab-pane fade @if($activeContentSection === 'intro') show active @endif" id="services-intro-pane" role="tabpanel" aria-labelledby="services-intro-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="page_title">Browser tab title</label>
                        <input type="text" name="page_title" id="page_title" class="form-control" value="{{ old('page_title', $data['page_title'] ?? '') }}" maxlength="120" placeholder="e.g. Services">
                        <div class="form-text">Shown before your site name in the visitor’s browser tab. Leave blank to use the default word for this page.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="intro_eyebrow">Eyebrow</label>
                        <input type="text" name="intro_eyebrow" id="intro_eyebrow" class="form-control" value="{{ old('intro_eyebrow', $data['intro_eyebrow'] ?? '') }}" maxlength="120">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="intro_title">Title <span class="text-muted fw-normal">(line breaks allowed)</span></label>
                        <textarea name="intro_title" id="intro_title" class="form-control" rows="4" maxlength="500">{{ old('intro_title', $data['intro_title'] ?? '') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="intro_body">Description</label>
                        <textarea name="intro_body" id="intro_body" class="form-control" rows="4" maxlength="2000">{{ old('intro_body', $data['intro_body'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'grid') show active @endif" id="services-grid-pane" role="tabpanel" aria-labelledby="services-grid-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="service_cta_prefix">Link text before service title</label>
                        <input type="text" name="service_cta_prefix" id="service_cta_prefix" class="form-control" value="{{ old('service_cta_prefix', $data['service_cta_prefix'] ?? '') }}" maxlength="120">
                        <div class="form-text">Example: “Enquire about” + service title.</div>
                    </div>
                    <hr class="my-4">
                    <p class="text-muted small fw-semibold mb-3">Empty state (when no services are published)</p>
                    <div class="mb-3">
                        <label class="form-label" for="empty_message">Message</label>
                        <input type="text" name="empty_message" id="empty_message" class="form-control" value="{{ old('empty_message', $data['empty_message'] ?? '') }}" maxlength="500">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="empty_btn_text">Button text</label>
                            <input type="text" name="empty_btn_text" id="empty_btn_text" class="form-control" value="{{ old('empty_btn_text', $data['empty_btn_text'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="empty_btn_url">Button URL</label>
                            <input type="text" name="empty_btn_url" id="empty_btn_url" class="form-control" value="{{ old('empty_btn_url', $data['empty_btn_url'] ?? '') }}" maxlength="1000" placeholder="Leave blank for Contact page">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'bottom') show active @endif" id="services-bottom-pane" role="tabpanel" aria-labelledby="services-bottom-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="bottom_eyebrow">Eyebrow</label>
                        <input type="text" name="bottom_eyebrow" id="bottom_eyebrow" class="form-control" value="{{ old('bottom_eyebrow', $data['bottom_eyebrow'] ?? '') }}" maxlength="120">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="bottom_heading">Heading <span class="text-muted fw-normal">(line breaks allowed)</span></label>
                        <textarea name="bottom_heading" id="bottom_heading" class="form-control" rows="3" maxlength="500">{{ old('bottom_heading', $data['bottom_heading'] ?? '') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="bottom_body">Supporting line <span class="text-muted fw-normal">(optional)</span></label>
                        <textarea name="bottom_body" id="bottom_body" class="form-control" rows="2" maxlength="1000">{{ old('bottom_body', $data['bottom_body'] ?? '') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="bottom_btn_text">Button text</label>
                            <input type="text" name="bottom_btn_text" id="bottom_btn_text" class="form-control" value="{{ old('bottom_btn_text', $data['bottom_btn_text'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="bottom_btn_url">Button URL</label>
                            <input type="text" name="bottom_btn_url" id="bottom_btn_url" class="form-control" value="{{ old('bottom_btn_url', $data['bottom_btn_url'] ?? '') }}" maxlength="1000" placeholder="Leave blank for Contact page">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection

@section('scripts')
@include('admin.partials.theme-section-tab-persist-script', ['tabListId' => 'servicesPageSectionTabs', 'inputId' => 'services_page_active_section'])
@endsection
