@extends('layouts.frontend')
@section('title', 'Services')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">What we offer</span>
        <h1 class="page-hero-title-wide">Our Services</h1>
        <p>Hand-finished plaster, bespoke media walls, and architectural details — crafted for homes and commercial spaces.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Services</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row g-4">
            @forelse($services as $service)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('services.show', $service->slug) }}" class="service-grid-card">
                    <div class="service-grid-card__media">
                        @if($service->image)
                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}" loading="lazy" decoding="async">
                        @else
                            <div class="service-grid-card__placeholder" aria-hidden="true">
                                <i class="{{ $service->icon ?? 'fas fa-paint-brush' }}"></i>
                            </div>
                        @endif
                    </div>
                    <div class="service-grid-card__body">
                        <span class="service-grid-eyebrow">Service</span>
                        <h3>{{ $service->title }}</h3>
                        @if($service->short_description)
                            <p>{{ $service->short_description }}</p>
                        @endif
                        <span class="service-grid-card__link">
                            Discover <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                        </span>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5" style="color:var(--ink-light);">
                <i class="fas fa-concierge-bell fa-3x mb-3" style="color:var(--border);"></i>
                <p>No services available yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<div class="cta-strip">
    <div class="container text-center">
        <span class="eyebrow">BEGIN</span>
        <h2>Not sure which service fits?</h2>
        <p class="mb-5">Talk to us — we'll help you find the right solution.</p>
        <a href="{{ route('contact') }}" class="btn-white">Contact us <i class="fas fa-arrow-right" style="font-size:.75rem;"></i></a>
    </div>
</div>

@endsection
