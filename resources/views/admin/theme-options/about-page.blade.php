@extends('layouts.admin')

@section('title', 'About page')

@section('styles')
@include('admin.partials.theme-section-tabs-styles')
@endsection

@section('content')
<div class="page-header-bar">
    <div>
        <h1>About page</h1>
        <p class="text-muted mb-0 small">Content for the <strong>About</strong> page template. SEO title/description still come from the <a href="{{ route('admin.pages.index') }}">Pages</a> entry. On <strong>Story &amp; images</strong>, main and accent collage photos can fall back to the first two active <a href="{{ route('admin.gallery.index') }}">Gallery</a> items if not uploaded. The wide workshop photo is managed under <strong>Workshop</strong>.</p>
    </div>
</div>

@include('admin.partials.theme-content-nav', ['active' => 'about'])

@php
    $activeContentSection = \App\Support\ThemeContentPageTabs::normalizeIn(
        \App\Support\ThemeContentPageTabs::ABOUT,
        'intro',
        old('about_page_active_section', $activeContentSection)
    );
@endphp

<form action="{{ route('admin.theme-options.about.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="about_page_active_section" id="about_page_active_section" value="{{ $activeContentSection }}">

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs card-header-tabs theme-section-tabs" id="aboutPageSectionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'intro') active @endif" id="about-intro-tab" data-bs-toggle="tab" data-bs-target="#about-intro-pane" aria-controls="about-intro-pane" data-theme-section="intro" aria-selected="{{ $activeContentSection === 'intro' ? 'true' : 'false' }}">Intro</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'story') active @endif" id="about-story-tab" data-bs-toggle="tab" data-bs-target="#about-story-pane" aria-controls="about-story-pane" data-theme-section="story" aria-selected="{{ $activeContentSection === 'story' ? 'true' : 'false' }}">Story &amp; images</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'stats') active @endif" id="about-stats-tab" data-bs-toggle="tab" data-bs-target="#about-stats-pane" aria-controls="about-stats-pane" data-theme-section="stats" aria-selected="{{ $activeContentSection === 'stats' ? 'true' : 'false' }}">Stats</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" role="tab" class="nav-link @if($activeContentSection === 'workshop') active @endif" id="about-workshop-tab" data-bs-toggle="tab" data-bs-target="#about-workshop-pane" aria-controls="about-workshop-pane" data-theme-section="workshop" aria-selected="{{ $activeContentSection === 'workshop' ? 'true' : 'false' }}">Workshop</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content theme-section-tabs__panels" id="aboutPageTabsContent">
                <div class="tab-pane fade @if($activeContentSection === 'intro') show active @endif" id="about-intro-pane" role="tabpanel" aria-labelledby="about-intro-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="intro_eyebrow">Eyebrow</label>
                        <input type="text" name="intro_eyebrow" id="intro_eyebrow" class="form-control" value="{{ old('intro_eyebrow', $data['intro_eyebrow'] ?? '') }}" maxlength="120">
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="intro_title">Title <span class="text-muted fw-normal">(line breaks allowed)</span></label>
                        <textarea name="intro_title" id="intro_title" class="form-control" rows="3" maxlength="500">{{ old('intro_title', $data['intro_title'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'story') show active @endif" id="about-story-pane" role="tabpanel" aria-labelledby="about-story-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="story_heading">Story heading</label>
                        <input type="text" name="story_heading" id="story_heading" class="form-control" value="{{ old('story_heading', $data['story_heading'] ?? '') }}" maxlength="255">
                    </div>
                    @foreach(['1' => 'First paragraph', '2' => 'Second paragraph', '3' => 'Third paragraph'] as $n => $label)
                        <div class="mb-3">
                            <label class="form-label" for="story_body_{{ $n }}">{{ $label }}</label>
                            <textarea name="story_body_{{ $n }}" id="story_body_{{ $n }}" class="form-control" rows="3" maxlength="3000">{{ old('story_body_'.$n, $data['story_body_'.$n] ?? '') }}</textarea>
                        </div>
                    @endforeach

                    <hr class="my-4">
                    <p class="text-muted small fw-semibold mb-3">Collage images (optional — main and accent only; leave empty to use the first two active gallery items)</p>

                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label">Main (large collage)</label>
                        @if(!empty($data['image_main'] ?? null))
                            <div class="mb-2"><img src="{{ asset('storage/'.$data['image_main']) }}" alt="" class="img-preview" style="max-height:100px;border-radius:8px;"></div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="remove_about_image_main" id="remove_about_image_main" value="1" {{ old('remove_about_image_main') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remove_about_image_main">Remove current image</label>
                            </div>
                        @endif
                        <input type="file" name="about_image_main" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
                        <label class="form-label small text-muted mt-2" for="image_main_alt">Alt text</label>
                        <input type="text" name="image_main_alt" id="image_main_alt" class="form-control form-control-sm" value="{{ old('image_main_alt', $data['image_main_alt'] ?? '') }}" maxlength="255">
                    </div>
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label">Accent (small collage)</label>
                        @if(!empty($data['image_accent'] ?? null))
                            <div class="mb-2"><img src="{{ asset('storage/'.$data['image_accent']) }}" alt="" class="img-preview" style="max-height:100px;border-radius:8px;"></div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="remove_about_image_accent" id="remove_about_image_accent" value="1" {{ old('remove_about_image_accent') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remove_about_image_accent">Remove current image</label>
                            </div>
                        @endif
                        <input type="file" name="about_image_accent" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
                        <label class="form-label small text-muted mt-2" for="image_accent_alt">Alt text</label>
                        <input type="text" name="image_accent_alt" id="image_accent_alt" class="form-control form-control-sm" value="{{ old('image_accent_alt', $data['image_accent_alt'] ?? '') }}" maxlength="255">
                    </div>
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'stats') show active @endif" id="about-stats-pane" role="tabpanel" aria-labelledby="about-stats-tab" tabindex="0">
                    @foreach([1, 2, 3] as $i)
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label" for="stat{{ $i }}_num">Stat {{ $i }} — number</label>
                                <input type="text" name="stat{{ $i }}_num" id="stat{{ $i }}_num" class="form-control" value="{{ old('stat'.$i.'_num', $data['stat'.$i.'_num'] ?? '') }}" maxlength="40">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label" for="stat{{ $i }}_label">Stat {{ $i }} — label</label>
                                <input type="text" name="stat{{ $i }}_label" id="stat{{ $i }}_label" class="form-control" value="{{ old('stat'.$i.'_label', $data['stat'.$i.'_label'] ?? '') }}" maxlength="120">
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="tab-pane fade @if($activeContentSection === 'workshop') show active @endif" id="about-workshop-pane" role="tabpanel" aria-labelledby="about-workshop-tab" tabindex="0">
                    <div class="mb-3">
                        <label class="form-label" for="workshop_eyebrow">Eyebrow</label>
                        <input type="text" name="workshop_eyebrow" id="workshop_eyebrow" class="form-control" value="{{ old('workshop_eyebrow', $data['workshop_eyebrow'] ?? '') }}" maxlength="120">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="workshop_heading">Heading</label>
                        <input type="text" name="workshop_heading" id="workshop_heading" class="form-control" value="{{ old('workshop_heading', $data['workshop_heading'] ?? '') }}" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="workshop_body">Body</label>
                        <textarea name="workshop_body" id="workshop_body" class="form-control" rows="3" maxlength="2000">{{ old('workshop_body', $data['workshop_body'] ?? '') }}</textarea>
                    </div>
                    <hr class="my-4 text-secondary opacity-25">
                    <div class="mb-3">
                        <label class="form-label">Workshop (wide)</label>
                        <p class="text-muted small mb-2">Wide image beside the workshop copy on the public About page.</p>
                        @if(!empty($data['image_studio'] ?? null))
                            <div class="mb-2"><img src="{{ asset('storage/'.$data['image_studio']) }}" alt="" class="img-preview" style="max-height:100px;border-radius:8px;"></div>
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="remove_about_image_studio" id="remove_about_image_studio" value="1" {{ old('remove_about_image_studio') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remove_about_image_studio">Remove current image</label>
                            </div>
                        @endif
                        <input type="file" name="about_image_studio" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp,.svg">
                        <label class="form-label small text-muted mt-2" for="image_studio_alt">Alt text</label>
                        <input type="text" name="image_studio_alt" id="image_studio_alt" class="form-control form-control-sm" value="{{ old('image_studio_alt', $data['image_studio_alt'] ?? '') }}" maxlength="255">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="workshop_btn_text">Button text</label>
                            <input type="text" name="workshop_btn_text" id="workshop_btn_text" class="form-control" value="{{ old('workshop_btn_text', $data['workshop_btn_text'] ?? '') }}" maxlength="120">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="workshop_btn_url">Button URL</label>
                            <input type="text" name="workshop_btn_url" id="workshop_btn_url" class="form-control" value="{{ old('workshop_btn_url', $data['workshop_btn_url'] ?? '') }}" maxlength="1000" placeholder="Leave blank for Contact page">
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
@include('admin.partials.theme-section-tab-persist-script', ['tabListId' => 'aboutPageSectionTabs', 'inputId' => 'about_page_active_section'])
@endsection
