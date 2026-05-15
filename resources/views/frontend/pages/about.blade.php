@extends('layouts.frontend')

@section('title', trim((string) ($page->meta_title ?? '')) !== '' ? $page->meta_title : $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('styles')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content')

@isset($aboutPage)
@php($ap = $aboutPage)
<div class="cms-page-layout cms-page-layout--full cms-page-layout--about">
    @include('frontend.pages.partials.cms-hero', [
        'page' => $page,
        'customHeroEyebrow' => $ap['intro_eyebrow'] ?? '',
        'customHeroTitle' => $ap['intro_title'] ?? '',
    ])

    <div class="cms-builder cms-builder--about">
        <div class="container-fluid cms-page-container cms-builder__shell px-3 px-sm-4 px-xl-5">

@if(
    trim((string) ($ap['image_main_display'] ?? '')) !== ''
    || trim((string) ($ap['image_accent_display'] ?? '')) !== ''
    || trim((string) ($ap['story_heading'] ?? '')) !== ''
    || trim((string) ($ap['story_body_1'] ?? '')) !== ''
    || trim((string) ($ap['story_body_2'] ?? '')) !== ''
    || trim((string) ($ap['story_body_3'] ?? '')) !== ''
)
            <section class="cms-builder-band cms-about-band--story" aria-label="Story">
                <div class="row g-4 g-xl-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="home-atelier-collage cms-about-collage" aria-hidden="true">
                            <div class="home-atelier-collage__accent"></div>
                            <div class="home-atelier-collage__shape home-atelier-collage__shape--halo"></div>
                            <div class="home-atelier-collage__shape home-atelier-collage__shape--dot"></div>
                            <div class="home-atelier-collage__shape home-atelier-collage__shape--square"></div>
                            @if(trim((string) ($ap['image_main_display'] ?? '')) !== '')
                                <div class="home-atelier-collage__main">
                                    <img src="{{ $ap['image_main_display'] }}"
                                         alt="{{ $ap['image_main_alt'] ?? '' }}"
                                         loading="lazy" decoding="async"
                                         class="home-atelier-collage__img home-atelier-collage__img--primary img-fluid img-fallback"
                                         data-fallback="{{ $ap['image_main_fallback'] ?? '' }}">
                                </div>
                            @endif
                            @if(trim((string) ($ap['image_accent_display'] ?? '')) !== '')
                                <div class="home-atelier-collage__float">
                                    <img src="{{ $ap['image_accent_display'] }}"
                                         alt="{{ $ap['image_accent_alt'] ?? '' }}"
                                         loading="lazy" decoding="async"
                                         class="home-atelier-collage__img home-atelier-collage__img--inset img-fluid img-fallback"
                                         data-fallback="{{ $ap['image_accent_fallback'] ?? '' }}">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="cms-about-story-copy">
                            @if(trim((string) ($ap['story_heading'] ?? '')) !== '')
                                <h2 class="cms-about-story-copy__title">{{ $ap['story_heading'] }}</h2>
                            @endif
                            @if(trim((string) ($ap['story_body_1'] ?? '')) !== '')
                                <p>{{ $ap['story_body_1'] }}</p>
                            @endif
                            @if(trim((string) ($ap['story_body_2'] ?? '')) !== '')
                                <p>{{ $ap['story_body_2'] }}</p>
                            @endif
                            @if(trim((string) ($ap['story_body_3'] ?? '')) !== '')
                                <p>{{ $ap['story_body_3'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
@endif

@if(
    trim((string) ($ap['stat1_num'] ?? '')) !== '' || trim((string) ($ap['stat1_label'] ?? '')) !== ''
    || trim((string) ($ap['stat2_num'] ?? '')) !== '' || trim((string) ($ap['stat2_label'] ?? '')) !== ''
    || trim((string) ($ap['stat3_num'] ?? '')) !== '' || trim((string) ($ap['stat3_label'] ?? '')) !== ''
)
            <section class="cms-about-stats" aria-label="Highlights">
                <div class="cms-about-stats__grid">
                    @if(trim((string) ($ap['stat1_num'] ?? '')) !== '' || trim((string) ($ap['stat1_label'] ?? '')) !== '')
                        <div class="cms-about-stats__item">
                            <span class="cms-about-stats__num">{{ $ap['stat1_num'] }}</span>
                            <span class="cms-about-stats__label">{{ $ap['stat1_label'] }}</span>
                        </div>
                    @endif
                    @if(trim((string) ($ap['stat2_num'] ?? '')) !== '' || trim((string) ($ap['stat2_label'] ?? '')) !== '')
                        <div class="cms-about-stats__item">
                            <span class="cms-about-stats__num">{{ $ap['stat2_num'] }}</span>
                            <span class="cms-about-stats__label">{{ $ap['stat2_label'] }}</span>
                        </div>
                    @endif
                    @if(trim((string) ($ap['stat3_num'] ?? '')) !== '' || trim((string) ($ap['stat3_label'] ?? '')) !== '')
                        <div class="cms-about-stats__item">
                            <span class="cms-about-stats__num">{{ $ap['stat3_num'] }}</span>
                            <span class="cms-about-stats__label">{{ $ap['stat3_label'] }}</span>
                        </div>
                    @endif
                </div>
            </section>
@endif

@if(
    trim((string) ($ap['workshop_eyebrow'] ?? '')) !== ''
    || trim((string) ($ap['workshop_heading'] ?? '')) !== ''
    || trim((string) ($ap['workshop_body'] ?? '')) !== ''
    || trim((string) ($ap['image_studio_display'] ?? '')) !== ''
    || (trim((string) ($ap['workshop_btn_text'] ?? '')) !== '' && trim((string) ($ap['workshop_btn_href'] ?? '')) !== '')
)
            <section class="cms-builder-band cms-builder-band--alt cms-about-band--workshop" aria-label="Workshop">
                <div class="row g-4 g-xl-5 align-items-center">
                    <div class="col-lg-5 order-2 order-lg-1">
                        <div class="cms-about-workshop-copy">
                            @if(trim((string) ($ap['workshop_eyebrow'] ?? '')) !== '')
                                <span class="cms-builder-eyebrow">{{ $ap['workshop_eyebrow'] }}</span>
                            @endif
                            @if(trim((string) ($ap['workshop_heading'] ?? '')) !== '')
                                <h2 class="cms-about-workshop-copy__title">{{ $ap['workshop_heading'] }}</h2>
                            @endif
                            @if(trim((string) ($ap['workshop_body'] ?? '')) !== '')
                                <p>{{ $ap['workshop_body'] }}</p>
                            @endif
                            @if(trim((string) ($ap['workshop_btn_text'] ?? '')) !== '' && trim((string) ($ap['workshop_btn_href'] ?? '')) !== '')
                                <a href="{{ $ap['workshop_btn_href'] }}" class="btn btn-dark cms-builder-btn mt-2">{{ $ap['workshop_btn_text'] }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-7 order-1 order-lg-2">
                        @if(trim((string) ($ap['image_studio_display'] ?? '')) !== '')
                            <div class="cms-about-workshop__frame media-frame">
                                <img src="{{ $ap['image_studio_display'] }}"
                                     alt="{{ $ap['image_studio_alt'] ?? '' }}"
                                     class="img-fluid w-100 img-fallback"
                                     loading="lazy" decoding="async"
                                     data-fallback="{{ $ap['image_studio_fallback'] ?? '' }}">
                            </div>
                        @endif
                    </div>
                </div>
            </section>
@endif

        </div>
    </div>
</div>
@endisset

@endsection

@section('scripts')
<script>
document.querySelectorAll('img.img-fallback').forEach(function(img) {
    img.addEventListener('error', function() {
        var fb = this.getAttribute('data-fallback');
        if (fb) { this.src = fb; }
    });
});
</script>
@endsection
