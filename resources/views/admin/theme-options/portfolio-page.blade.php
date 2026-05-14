@extends('layouts.admin')

@section('title', 'Portfolio page')

@section('styles')
@include('admin.partials.theme-section-tabs-styles')
@endsection

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Portfolio page</h1>
        <p class="text-muted mb-0 small">Public listing at <code>/portfolio</code>. Section tabs organize the form; a single save updates every section. Individual projects are not edited here anymore; the live site still shows existing portfolio records from the database.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'portfolio'])

@php
    $activeContentSection = \App\Support\ThemeContentPageTabs::normalizeIn(
        \App\Support\ThemeContentPageTabs::LISTING_INTRO_GRID_BOTTOM,
        'intro',
        old('portfolio_page_active_section', $activeContentSection)
    );
@endphp

<form action="{{ route('admin.theme-options.portfolio.update') }}" method="POST">
    @csrf
    <input type="hidden" name="portfolio_page_active_section" id="portfolio_page_active_section" value="{{ $activeContentSection }}">

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs card-header-tabs theme-section-tabs" id="portfolioPageSectionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'intro') active @endif" id="portfolio-intro-tab" data-bs-toggle="tab" data-bs-target="#portfolio-intro-pane" aria-controls="portfolio-intro-pane" data-theme-section="intro" aria-selected="{{ $activeContentSection === 'intro' ? 'true' : 'false' }}">
                        Intro
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'grid') active @endif" id="portfolio-grid-tab" data-bs-toggle="tab" data-bs-target="#portfolio-grid-pane" aria-controls="portfolio-grid-pane" data-theme-section="grid" aria-selected="{{ $activeContentSection === 'grid' ? 'true' : 'false' }}">
                        Grid
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'bottom') active @endif" id="portfolio-bottom-tab" data-bs-toggle="tab" data-bs-target="#portfolio-bottom-pane" aria-controls="portfolio-bottom-pane" data-theme-section="bottom" aria-selected="{{ $activeContentSection === 'bottom' ? 'true' : 'false' }}">
                        Bottom CTA
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content theme-section-tabs__panels" id="portfolioPageTabsContent">
                <div class="tab-pane fade @if($activeContentSection === 'intro') show active @endif" id="portfolio-intro-pane" role="tabpanel" aria-labelledby="portfolio-intro-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="intro_eyebrow">Eyebrow</label>
                        <input type="text" name="intro_eyebrow" id="intro_eyebrow" class="form-control" value="{{ old('intro_eyebrow', $data['intro_eyebrow'] ?? '') }}" maxlength="120">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="intro_title">Title</label>
                        <input type="text" name="intro_title" id="intro_title" class="form-control" value="{{ old('intro_title', $data['intro_title'] ?? '') }}" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="intro_body">Lead paragraph</label>
                        <textarea name="intro_body" id="intro_body" class="form-control" rows="3" maxlength="2000">{{ old('intro_body', $data['intro_body'] ?? '') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="breadcrumb_current">Breadcrumb — current page label</label>
                        <input type="text" name="breadcrumb_current" id="breadcrumb_current" class="form-control" value="{{ old('breadcrumb_current', $data['breadcrumb_current'] ?? '') }}" maxlength="120">
                        <div class="form-text">Shown after “Home” in the hero breadcrumb.</div>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'grid') show active @endif" id="portfolio-grid-pane" role="tabpanel" aria-labelledby="portfolio-grid-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="filter_all_label">“All” filter button label</label>
                        <input type="text" name="filter_all_label" id="filter_all_label" class="form-control" value="{{ old('filter_all_label', $data['filter_all_label'] ?? '') }}" maxlength="80">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="label_real_project">Badge — real project</label>
                            <input type="text" name="label_real_project" id="label_real_project" class="form-control" value="{{ old('label_real_project', $data['label_real_project'] ?? '') }}" maxlength="80">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="label_reference">Badge — reference</label>
                            <input type="text" name="label_reference" id="label_reference" class="form-control" value="{{ old('label_reference', $data['label_reference'] ?? '') }}" maxlength="80">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="card_link_text">Card link line <span class="text-muted fw-normal">(before arrow)</span></label>
                        <input type="text" name="card_link_text" id="card_link_text" class="form-control" value="{{ old('card_link_text', $data['card_link_text'] ?? '') }}" maxlength="120">
                    </div>
                    <hr class="my-4">
                    <p class="text-muted small fw-semibold mb-3">Empty state</p>
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

                <div class="tab-pane fade @if($activeContentSection === 'bottom') show active @endif" id="portfolio-bottom-pane" role="tabpanel" aria-labelledby="portfolio-bottom-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="bottom_heading">Heading</label>
                        <input type="text" name="bottom_heading" id="bottom_heading" class="form-control" value="{{ old('bottom_heading', $data['bottom_heading'] ?? '') }}" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="bottom_body">Supporting paragraph</label>
                        <textarea name="bottom_body" id="bottom_body" class="form-control" rows="3" maxlength="1000">{{ old('bottom_body', $data['bottom_body'] ?? '') }}</textarea>
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
@include('admin.partials.theme-section-tab-persist-script', ['tabListId' => 'portfolioPageSectionTabs', 'inputId' => 'portfolio_page_active_section'])
@endsection
