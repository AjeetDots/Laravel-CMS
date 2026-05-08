@extends('layouts.frontend')
@section('title', $finish->title)
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">Finish</span>
        <h1 class="page-hero-title-wide">{{ $finish->title }}</h1>
        @if($finish->description)
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($finish->description), 220) }}</p>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('finishes') }}">Finishes</a></li>
                <li class="breadcrumb-item active">{{ $finish->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($finish->thumbnail_url)
                <div class="media-frame media-frame--short service-detail-lead-img mb-4">
                    <img src="{{ $finish->thumbnail_url }}" alt="{{ $finish->title }}" loading="lazy" decoding="async">
                </div>
                @endif

                <div class="service-detail-body">
                    @if($finish->description)
                        {!! $finish->description !!}
                    @endif
                </div>

                @if($finish->use_cases)
                    <div class="mt-5">
                        <span class="eyebrow">Ideal for</span>
                        <p class="fs-5 mt-2" style="font-family:'Cormorant Garamond',serif;">{{ $finish->use_cases }}</p>
                    </div>
                @endif

                @php
                    $galleryExtra = $finish->gallery_urls ?? [];
                    if (!$finish->cover_image && count($galleryExtra)) {
                        $galleryExtra = array_slice($galleryExtra, 1);
                    }
                @endphp
                @if(count($galleryExtra))
                <div class="mt-5">
                    <h3 class="h5 mb-4" style="font-family:'Playfair Display',serif;">Gallery</h3>
                    <div class="row g-3">
                        @foreach($galleryExtra as $url)
                        <div class="col-6 col-md-4">
                            <div class="media-frame" style="aspect-ratio:1;">
                                <img src="{{ $url }}" alt="" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($finish->tags && count($finish->tags))
                <div class="mt-4 d-flex flex-wrap gap-2">
                    @foreach($finish->tags as $tag)
                        <span class="badge rounded-pill" style="background:rgba(201,168,76,.15);color:#5c4a2a;font-weight:500;padding:.45rem .85rem;">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="service-sidebar-wrap">
                    <div class="service-sidebar-card">
                        <span class="eyebrow">Interested in this finish?</span>
                        <h4>Get in touch</h4>
                        <p class="sub">Tell us about your space and we’ll recommend options and samples.</p>
                        <a href="{{ route('contact') }}" class="btn-primary-site w-100 justify-content-center mb-3">
                            Get in touch <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
                        </a>
                        <a href="{{ route('finishes') }}" class="btn-outline-site w-100 justify-content-center">
                            All finishes
                        </a>
                    </div>

                    @if($finish->services->count())
                    <div class="mt-4">
                        <p class="service-sidebar-more-label">Related services</p>
                        @foreach($finish->services as $svc)
                        <a href="{{ route('services.show', $svc->slug) }}" class="service-sidebar-link text-decoration-none">
                            <i class="fas fa-concierge-bell"></i>
                            {{ $svc->title }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@if($related->count())
<section class="section section-soft">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col">
                <span class="eyebrow">More finishes</span>
                <h2 class="h3 mb-0" style="font-family:'Playfair Display',serif;">You may also like</h2>
            </div>
        </div>
        <div class="row g-4">
            @foreach($related as $r)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('finishes.show', $r->slug) }}" class="service-grid-card">
                    <div class="service-grid-card__media">
                        @if($r->thumbnail_url)
                            <img src="{{ $r->thumbnail_url }}" alt="{{ $r->title }}" loading="lazy">
                        @else
                            <div class="service-grid-card__placeholder"><i class="fas fa-paint-brush"></i></div>
                        @endif
                    </div>
                    <div class="service-grid-card__body">
                        <h3 class="h6">{{ $r->title }}</h3>
                        <span class="service-grid-card__link">View <i class="fas fa-arrow-right" style="font-size:.65rem;"></i></span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
