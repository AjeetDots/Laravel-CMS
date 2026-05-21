@extends('layouts.frontend')

@section('title', trim((string) ($page->meta_title ?? '')) !== '' ? $page->meta_title : $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid page-about')

@section('styles')
<link href="{{ asset('css/about.css') }}" rel="stylesheet">
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('content')

@isset($aboutPage)
@php($ap = $aboutPage)
@if(trim((string) ($ap['intro_eyebrow'] ?? '')) !== '' || trim((string) ($ap['intro_title'] ?? '')) !== '')
<section class="about-intro">
    <div class="container">
        @if(trim((string) ($ap['intro_eyebrow'] ?? '')) !== '')
        <span class="finishes-intro__eyebrow">{{ $ap['intro_eyebrow'] }}</span>
        @endif
        @if(trim((string) ($ap['intro_title'] ?? '')) !== '')
        <h1 class="finishes-intro__title">{!! nl2br(e($ap['intro_title'])) !!}</h1>
        @endif
    </div>
</section>
@endif

@if(
    trim((string) ($ap['image_main_display'] ?? '')) !== ''
    || trim((string) ($ap['image_accent_display'] ?? '')) !== ''
    || trim((string) ($ap['story_heading'] ?? '')) !== ''
    || trim((string) ($ap['story_body_1'] ?? '')) !== ''
    || trim((string) ($ap['story_body_2'] ?? '')) !== ''
    || trim((string) ($ap['story_body_3'] ?? '')) !== ''
)
<section class="about-story">
    <div class="container">
        <div class="row g-3 g-md-5 align-items-center">
            <div class="col-lg-6">
                <!-- <div class="about-story__collage">
                    <span class="about-story__shape about-story__shape--arc" aria-hidden="true"></span>
                    <span class="about-story__shape about-story__shape--bar" aria-hidden="true"></span>
                    <span class="about-story__shape about-story__shape--square" aria-hidden="true"></span>
                    <span class="about-story__shape about-story__shape--dot" aria-hidden="true"></span>

                    @if(trim((string) ($ap['image_main_display'] ?? '')) !== '')
                    <div class="about-story__image-main">
                        <img src="{{ $ap['image_main_display'] }}" alt="{{ $ap['image_main_alt'] ?? '' }}" class="img-fallback" data-fallback="{{ $ap['image_main_fallback'] ?? '' }}">
                    </div>
                    @endif
                    @if(trim((string) ($ap['image_accent_display'] ?? '')) !== '')
                    <div class="about-story__image-accent">
                        <img src="{{ $ap['image_accent_display'] }}" alt="{{ $ap['image_accent_alt'] ?? '' }}" class="img-fallback" data-fallback="{{ $ap['image_accent_fallback'] ?? '' }}">
                    </div>
                    @endif
                </div> -->
                 <div class="home-atelier-collage" aria-hidden="true">
                    <div class="home-atelier-collage__accent"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--halo"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--dot"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--square"></div>
                     @if(trim((string) ($ap['image_main_display'] ?? '')) !== '')
                    <div class="home-atelier-collage__main">
                        <img src="{{ $ap['image_main_display'] }}"
                             alt="{{ $ap['image_main_alt'] ?? '' }}"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--primary img-fluid">
                    </div>
                    <div class="blue_Section">
                        <img src="../images/blue.png" class="img-fluid"/>
                    </div>
                    @endif
                   @if(trim((string) ($ap['image_accent_display'] ?? '')) !== '')
                    <div class="home-atelier-collage__float">
                        <img src="{{ $ap['image_accent_display'] }}"
                             alt="{{ $ap['image_accent_alt'] ?? '' }}"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--inset img-fluid">
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-story__content">
                    @if(trim((string) ($ap['story_heading'] ?? '')) !== '')
                    <h2 class="home-atelier-headline mb-4">{{ $ap['story_heading'] }}</h2>
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
    </div>
</section>
@endif

@if(
    trim((string) ($ap['stat1_num'] ?? '')) !== '' || trim((string) ($ap['stat1_label'] ?? '')) !== ''
    || trim((string) ($ap['stat2_num'] ?? '')) !== '' || trim((string) ($ap['stat2_label'] ?? '')) !== ''
    || trim((string) ($ap['stat3_num'] ?? '')) !== '' || trim((string) ($ap['stat3_label'] ?? '')) !== ''
)
<section class="about-stats-band">
    <div class="container">
        <div class="about-stats-band__grid">
            @if(trim((string) ($ap['stat1_num'] ?? '')) !== '' || trim((string) ($ap['stat1_label'] ?? '')) !== '')
            <div class="about-stats-band__item">
                <span class="about-stats-band__num"
                      data-count="{{ (int) filter_var($ap['stat1_num'] ?? '', FILTER_SANITIZE_NUMBER_INT) }}"
                      data-suffix="{{ preg_replace('/[\d]/','', $ap['stat1_num'] ?? '') }}">0{{ preg_replace('/[\d]/','', $ap['stat1_num'] ?? '') }}</span>
                <span class="about-stats-band__label">{{ $ap['stat1_label'] }}</span>
            </div>
            @endif
            @if(trim((string) ($ap['stat2_num'] ?? '')) !== '' || trim((string) ($ap['stat2_label'] ?? '')) !== '')
            <div class="about-stats-band__item">
                <span class="about-stats-band__num"
                      data-count="{{ (int) filter_var($ap['stat2_num'] ?? '', FILTER_SANITIZE_NUMBER_INT) }}"
                      data-suffix="{{ preg_replace('/[\d]/','', $ap['stat2_num'] ?? '') }}">0{{ preg_replace('/[\d]/','', $ap['stat2_num'] ?? '') }}</span>
                <span class="about-stats-band__label">{{ $ap['stat2_label'] }}</span>
            </div>
            @endif
            @if(trim((string) ($ap['stat3_num'] ?? '')) !== '' || trim((string) ($ap['stat3_label'] ?? '')) !== '')
            <div class="about-stats-band__item">
                <span class="about-stats-band__num"
                      data-count="{{ (int) filter_var($ap['stat3_num'] ?? '', FILTER_SANITIZE_NUMBER_INT) }}"
                      data-suffix="{{ preg_replace('/[\d]/','', $ap['stat3_num'] ?? '') }}">0{{ preg_replace('/[\d]/','', $ap['stat3_num'] ?? '') }}</span>
                <span class="about-stats-band__label">{{ $ap['stat3_label'] }}</span>
            </div>
            @endif
        </div>
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
<section class="about-workshop">
    <div class="container">
        <div class="row g-3 g-md-5 align-items-center">
            <div class="col-lg-5 g-3 g-md-5 col-sm-12 order-lg-1 order-xl-1 order-md-2 order-2">
                <div class="about-workshop__content reveal-left">
                    @if(trim((string) ($ap['workshop_eyebrow'] ?? '')) !== '')
                    <span class="about-workshop__eyebrow">{{ $ap['workshop_eyebrow'] }}</span>
                    @endif
                    @if(trim((string) ($ap['workshop_heading'] ?? '')) !== '')
                    <h2>{{ $ap['workshop_heading'] }}</h2>
                    @endif
                    @if(trim((string) ($ap['workshop_body'] ?? '')) !== '')
                    <p>{{ $ap['workshop_body'] }}</p>
                    @endif
                    @if(trim((string) ($ap['workshop_btn_text'] ?? '')) !== '' && trim((string) ($ap['workshop_btn_href'] ?? '')) !== '')
                    <a href="{{ $ap['workshop_btn_href'] }}" class="hero-btn hero-btn--gold home-atelier-btn">{{ $ap['workshop_btn_text'] }}</a>
                    @endif
                </div>
            </div>
            <div class="col-lg-7 col-sm-12 order-lg-2 order-xl-2 order-md-1 order-1">
                @if(trim((string) ($ap['image_studio_display'] ?? '')) !== '')
                <div class="about-workshop__image media-frame reveal-right">
                    <img src="{{ $ap['image_studio_display'] }}" alt="{{ $ap['image_studio_alt'] ?? '' }}" class="img-fallback" loading="lazy" decoding="async" data-fallback="{{ $ap['image_studio_fallback'] ?? '' }}">
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
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