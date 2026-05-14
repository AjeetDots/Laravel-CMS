@extends('layouts.frontend')
@section('title', $portfolio->title)
@section('body_class', 'nav-solid page-portfolio-detail')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">{{ $portfolio->project_type_label }}</span>
        <h1 class="page-hero-title-wide">{{ $portfolio->title }}</h1>
        @if($portfolio->description)
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($portfolio->description), 240) }}</p>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('portfolio') }}">Portfolio</a></li>
                <li class="breadcrumb-item active">{{ $portfolio->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($portfolio->cover_image)
                <div class="media-frame media-frame--short service-detail-lead-img mb-4">
                    <img src="{{ $portfolio->cover_image_url }}" alt="{{ $portfolio->title }}" loading="lazy" decoding="async">
                </div>
                @endif

                <div class="service-detail-body">
                    @if($portfolio->description)
                        {!! $portfolio->description !!}
                    @endif
                </div>

                @if(count($portfolio->gallery_urls ?? []))
                <div class="mt-5">
                    <h3 class="h5 mb-4" style="font-family:'Playfair Display',serif;">Project gallery</h3>
                    <div class="row g-3">
                        @foreach($portfolio->gallery_urls as $url)
                        <div class="col-6 col-md-4">
                            <div class="media-frame" style="aspect-ratio:4/3;">
                                <img src="{{ $url }}" alt="" loading="lazy" decoding="async" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($portfolio->tags && count($portfolio->tags))
                <div class="mt-4 d-flex flex-wrap gap-2 align-items-center">
                    <span class="small text-muted me-1">Tags:</span>
                    @foreach($portfolio->tags as $tag)
                        <span class="badge rounded-pill" style="background:rgba(201,168,76,.15);color:#5c4a2a;font-weight:500;padding:.45rem .85rem;">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="service-sidebar-wrap">
                    <div class="service-sidebar-card">
                        <span class="eyebrow">Like what you see?</span>
                        <h4>Discuss your project</h4>
                        <p class="sub">We can advise on finishes, scale, and lead times for work like this.</p>
                        <a href="{{ route('contact') }}" class="btn-primary-site w-100 justify-content-center mb-3">
                            Get in touch <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
                        </a>
                        <a href="{{ route('portfolio') }}" class="btn-outline-site w-100 justify-content-center">
                            Back to portfolio
                        </a>
                    </div>
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
                <span class="eyebrow">More work</span>
                <h2 class="h3 mb-0" style="font-family:'Playfair Display',serif;">Related projects</h2>
            </div>
        </div>
        <div class="row g-4">
            @foreach($related as $r)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('portfolio.show', $r->slug) }}" class="service-grid-card">
                    <div class="service-grid-card__media">
                        @if($r->cover_image)
                            <img src="{{ $r->cover_image_url }}" alt="{{ $r->title }}" loading="lazy">
                        @else
                            <div class="service-grid-card__placeholder"><i class="fas fa-briefcase"></i></div>
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
