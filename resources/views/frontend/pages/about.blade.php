@extends('layouts.frontend')

@section('title', $page->meta_title ?? 'About Us')

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid page-about')

@section('styles')
<link href="{{ asset('css/about.css') }}" rel="stylesheet">
@endsection

@section('content')

@php
    $aboutImages = \App\Models\GalleryItem::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->take(3)
        ->get();

    $storyMainImage = $aboutImages->get(0)?->image_url ?? 'https://placehold.co/900x1200/e5e0d8/6b6b65?text=Story+Image';
    $storyAccentImage = $aboutImages->get(1)?->image_url ?? 'https://placehold.co/640x480/e5e0d8/6b6b65?text=Accent+Image';
    $studioImage = $aboutImages->get(2)?->image_url ?? $storyMainImage;
@endphp

<section class="about-intro">
    <div class="container">
        <span class="about-intro__eyebrow">About the atelier</span>
        <h1 class="about-intro__title">A studio of artisans,<br>a craft of patience.</h1>
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
                        <img src="{{ $storyMainImage }}" alt="Bespoke interior finish" class="img-fallback" data-fallback="https://placehold.co/900x1200/e5e0d8/6b6b65?text=Story+Image">
                    </div>
                    <div class="about-story__image-accent">
                        <img src="{{ $storyAccentImage }}" alt="Signature polished finish" class="img-fallback" data-fallback="https://placehold.co/640x480/e5e0d8/6b6b65?text=Accent+Image">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-story__content">
                    <h2>Our story</h2>
                    <p>Trained in the lime-plaster traditions of Venice and refined across two decades of private and commercial commissions, our team has quietly built a reputation for finishes of unusual depth and consistency.</p>
                    <p>We work closely with leading interior designers and architects, and have been entrusted with environments for film, television and editorial productions where the surface itself must perform under the lens.</p>
                    <p>Every project begins with the room - its light, proportions, and intent - and ends with a finish made by hand.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="about-stats-band">
    <div class="container">
        <div class="about-stats-band__grid">
            <div class="about-stats-band__item">
                <span class="about-stats-band__num">20+</span>
                <span class="about-stats-band__label">Years of practice</span>
            </div>
            <div class="about-stats-band__item">
                <span class="about-stats-band__num">300+</span>
                <span class="about-stats-band__label">Private commissions</span>
            </div>
            <div class="about-stats-band__item">
                <span class="about-stats-band__num">40+</span>
                <span class="about-stats-band__label">Productions worked on</span>
            </div>
        </div>
    </div>
</section>

<section class="about-workshop">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="about-workshop__content">
                    <span class="about-workshop__eyebrow">Workshop &amp; studio</span>
                    <h2>Where the work begins.</h2>
                    <p>Samples, mock-ups and bespoke profiles are developed at our London studio before being installed on site by our master artisans.</p>
                    <a href="{{ route('contact') }}" class="about-workshop__btn">Visit the studio</a>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-workshop__image">
                    <img src="{{ $studioImage }}" alt="Workshop and studio finish development" class="img-fallback" data-fallback="https://placehold.co/1200x760/e5e0d8/6b6b65?text=Workshop+Image">
                </div>
            </div>
        </div>
    </div>
</section>

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
