@extends('layouts.frontend')
@section('title', $service->title)
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="finishes-intro__eyebrow">Our services</span>
        <h1 class="home-atelier-headline-inner">{{ $service->title }}</h1>
        @if(trim((string) ($service->short_description ?? '')) !== '')
        <p>{{ $service->short_description }}</p>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services') }}">Services</a></li>
                <li class="breadcrumb-item active">{{ $service->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                @if($service->image)
                <div class="media-frame media-frame--short service-detail-lead-img">
                    <img src="{{ $service->image_url }}" alt="{{ $service->title }}" loading="lazy" decoding="async">
                </div>
                @endif
                <div class="service-detail-body">
                    @if(trim(strip_tags((string) ($service->description ?? ''))) !== '')
                        {!! $service->description !!}
                    @elseif(trim((string) ($service->short_description ?? '')) !== '')
                        <p>{{ $service->short_description }}</p>
                    @endif
                </div>

                @if($service->finishes->count())
                <div class="mt-5 pt-4" style="border-top:1px solid rgba(0,0,0,.06);">
                    <span class="finishes-intro__eyebrow">Related finishes</span>
                    <h3 class="home-atelier-headline mb-3">Styles we often pair with this service</h3>
                    <div class="row g-3">
                        @foreach($service->finishes as $fin)
                        <div class="col-6 col-md-4">
                            <a href="{{ route('finishes.show', $fin->slug) }}" class="text-decoration-none d-block">
                                <div class="media-frame" style="aspect-ratio:1;border-radius:2px;">
                                    @if($fin->thumbnail_url)
                                        <img src="{{ $fin->thumbnail_url }}" alt="{{ $fin->title }}" class="w-100 h-100" style="object-fit:cover;" loading="lazy">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted"><i class="fas fa-paint-brush"></i></div>
                                    @endif
                                </div>
                                <p class="home-why-card__title-dark">{{ $fin->title }}</p>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-4">
                <div class="service-sidebar-wrap">
                    <div class="service-sidebar-card">
                        <span class="eyebrow">Ready to start?</span>
                        <h4>Let's work together</h4>
                        <p class="sub">Tell us about your project and we'll get back to you within 24 hours.</p>
                        <a href="{{ route('contact') }}" class="hero-btn hero-btn--gold home-atelier-btn w-100 mb-3 text-center">
                            Get in touch <i class="fas fa-arrow-right" style="font-size:.75rem;"></i>
                        </a>
                        <!-- <a href="{{ route('services') }}" class="btn-outline-site w-100 justify-content-center">
                            All services
                        </a> -->
                    </div>
                    @php $otherServices = \App\Models\Service::where('is_active',true)->where('slug','!=',$service->slug)->limit(4)->get(); @endphp
                    @if($otherServices->count())
                    <div class="mt-4">
                        <p class="service-sidebar-more-label">Other services</p>
                        @foreach($otherServices as $other)
                        <a href="{{ route('services.show',$other->slug) }}" class="service-sidebar-link text-decoration-none">
                            @if(trim((string) ($other->icon ?? '')) !== '')
                            <i class="{{ $other->icon }}"></i>
                            @endif
                            {{ $other->title }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
