@extends('layouts.admin')

@section('title', 'Gallery page')

@section('styles')
@include('admin.partials.theme-section-tabs-styles')
@endsection

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Gallery page</h1>
        <p class="text-muted mb-0 small">Public listing at <code>/gallery</code>. Section tabs organize the form; a single save updates every section. Images are managed under <a href="{{ route('admin.gallery.index') }}">Gallery</a> in Content.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'gallery'])

@php
    $activeContentSection = \App\Support\ThemeContentPageTabs::normalizeIn(
        \App\Support\ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM,
        'intro',
        old('gallery_page_active_section', $activeContentSection)
    );
@endphp

<form action="{{ route('admin.theme-options.gallery.update') }}" method="POST">
    @csrf
    <input type="hidden" name="gallery_page_active_section" id="gallery_page_active_section" value="{{ $activeContentSection }}">

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs card-header-tabs theme-section-tabs" id="galleryPageSectionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'intro') active @endif" id="gallery-intro-tab" data-bs-toggle="tab" data-bs-target="#gallery-intro-pane" aria-controls="gallery-intro-pane" data-theme-section="intro" aria-selected="{{ $activeContentSection === 'intro' ? 'true' : 'false' }}">
                        Intro
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'grid') active @endif" id="gallery-grid-tab" data-bs-toggle="tab" data-bs-target="#gallery-grid-pane" aria-controls="gallery-grid-pane" data-theme-section="grid" aria-selected="{{ $activeContentSection === 'grid' ? 'true' : 'false' }}">
                        Grid
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'bottom') active @endif" id="gallery-bottom-tab" data-bs-toggle="tab" data-bs-target="#gallery-bottom-pane" aria-controls="gallery-bottom-pane" data-theme-section="bottom" aria-selected="{{ $activeContentSection === 'bottom' ? 'true' : 'false' }}">
                        Bottom CTA
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content theme-section-tabs__panels" id="galleryPageTabsContent">
                <div class="tab-pane fade @if($activeContentSection === 'intro') show active @endif" id="gallery-intro-pane" role="tabpanel" aria-labelledby="gallery-intro-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="page_title">Browser tab title</label>
                        <input type="text" name="page_title" id="page_title" class="form-control" value="{{ old('page_title', $data['page_title'] ?? '') }}" maxlength="120" placeholder="e.g. Gallery">
                        <div class="form-text">Shown before your site name in the visitor’s browser tab. Leave blank to use the default word for this page.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="intro_eyebrow">Eyebrow</label>
                        <input type="text" name="intro_eyebrow" id="intro_eyebrow" class="form-control" value="{{ old('intro_eyebrow', $data['intro_eyebrow'] ?? '') }}" maxlength="120">
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="intro_title">Title</label>
                        <input type="text" name="intro_title" id="intro_title" class="form-control" value="{{ old('intro_title', $data['intro_title'] ?? '') }}" maxlength="255">
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'grid') show active @endif" id="gallery-grid-pane" role="tabpanel" aria-labelledby="gallery-grid-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="filter_all_label">“All” filter button label</label>
                        <input type="text" name="filter_all_label" id="filter_all_label" class="form-control" value="{{ old('filter_all_label', $data['filter_all_label'] ?? '') }}" maxlength="80">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="grid_category_fallback">Category label when item has no category</label>
                        <input type="text" name="grid_category_fallback" id="grid_category_fallback" class="form-control" value="{{ old('grid_category_fallback', $data['grid_category_fallback'] ?? '') }}" maxlength="120">
                    </div>
                    <hr class="my-4">
                    <p class="text-muted small fw-semibold mb-3">Empty state (no published items)</p>
                    <div class="mb-3">
                        <label class="form-label" for="empty_message">Message</label>
                        <input type="text" name="empty_message" id="empty_message" class="form-control" value="{{ old('empty_message', $data['empty_message'] ?? '') }}" maxlength="500">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="empty_btn_text">Button text <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" name="empty_btn_text" id="empty_btn_text" class="form-control" value="{{ old('empty_btn_text', $data['empty_btn_text'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="empty_btn_url">Button URL</label>
                            <input type="text" name="empty_btn_url" id="empty_btn_url" class="form-control" value="{{ old('empty_btn_url', $data['empty_btn_url'] ?? '') }}" maxlength="1000" placeholder="Leave blank for Contact page">
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'bottom') show active @endif" id="gallery-bottom-pane" role="tabpanel" aria-labelledby="gallery-bottom-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="bottom_heading">Heading</label>
                        <input type="text" name="bottom_heading" id="bottom_heading" class="form-control" value="{{ old('bottom_heading', $data['bottom_heading'] ?? '') }}" maxlength="255">
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
@include('admin.partials.theme-section-tab-persist-script', ['tabListId' => 'galleryPageSectionTabs', 'inputId' => 'gallery_page_active_section'])
@endsection
