@extends('layouts.frontend')

@section('title', $page->meta_title ?? 'About Us')

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid page-about')

@section('styles')
<link href="{{ asset('css/about.css') }}" rel="stylesheet">
@endsection

@section('content')

@isset($aboutPage)
<section class="about-intro">
    <div class="container">
        <span class="about-intro__eyebrow">{{ $aboutPage['intro_eyebrow'] }}</span>
        <h1 class="about-intro__title">{!! nl2br(e($aboutPage['intro_title'])) !!}</h1>
    </div>
</section>

<section class="about-story">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="about-story__collage">
                    <span class="about-story__shape about-story__shape--arc" aria-hidden="true"></span>
                    <span class="about-story__shape about-story__shape--bar" aria-hidden="true"></span>
                    <span class="about-story__shape about-story__shape--square" aria-hidden="true"></span>
                    <span class="about-story__shape about-story__shape--dot" aria-hidden="true"></span>

                    <div class="about-story__image-main">
                        <img src="{{ $aboutPage['image_main_display'] }}" alt="{{ $aboutPage['image_main_alt'] }}" class="img-fallback" data-fallback="{{ $aboutPage['image_main_fallback'] }}">
                    </div>
                    <div class="about-story__image-accent">
                        <img src="{{ $aboutPage['image_accent_display'] }}" alt="{{ $aboutPage['image_accent_alt'] }}" class="img-fallback" data-fallback="{{ $aboutPage['image_accent_fallback'] }}">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-story__content">
                    <h2>{{ $aboutPage['story_heading'] }}</h2>
                    <p>{{ $aboutPage['story_body_1'] }}</p>
                    <p>{{ $aboutPage['story_body_2'] }}</p>
                    <p>{{ $aboutPage['story_body_3'] }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-stats-band">
    <div class="container">
        <div class="about-stats-band__grid">
            <div class="about-stats-band__item">
                <span class="about-stats-band__num">{{ $aboutPage['stat1_num'] }}</span>
                <span class="about-stats-band__label">{{ $aboutPage['stat1_label'] }}</span>
            </div>
            <div class="about-stats-band__item">
                <span class="about-stats-band__num">{{ $aboutPage['stat2_num'] }}</span>
                <span class="about-stats-band__label">{{ $aboutPage['stat2_label'] }}</span>
            </div>
            <div class="about-stats-band__item">
                <span class="about-stats-band__num">{{ $aboutPage['stat3_num'] }}</span>
                <span class="about-stats-band__label">{{ $aboutPage['stat3_label'] }}</span>
            </div>
        </div>
    </div>
</section>

<section class="about-workshop">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="about-workshop__content">
                    <span class="about-workshop__eyebrow">{{ $aboutPage['workshop_eyebrow'] }}</span>
                    <h2>{{ $aboutPage['workshop_heading'] }}</h2>
                    <p>{{ $aboutPage['workshop_body'] }}</p>
                    <a href="{{ $aboutPage['workshop_btn_href'] }}" class="about-workshop__btn">{{ $aboutPage['workshop_btn_text'] }}</a>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-workshop__image">
                    <img src="{{ $aboutPage['image_studio_display'] }}" alt="{{ $aboutPage['image_studio_alt'] }}" class="img-fallback" data-fallback="{{ $aboutPage['image_studio_fallback'] }}">
                </div>
            </div>
        </div>
    </div>
</section>
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
