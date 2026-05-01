@extends('layouts.frontend')
@section('title', 'Services')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">What we offer</span>
        <h1>Our Services</h1>
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
                <div class="svc-card" style="display:flex;flex-direction:column;">
                    @if($service->image)
                    <div style="margin:-36px -30px 28px;overflow:hidden;border-radius:12px 12px 0 0;height:200px;">
                        <img src="{{ $service->image_url }}" alt="{{ $service->title }}" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    @endif
                    <div class="svc-icon">
                        <i class="{{ $service->icon ?? 'fas fa-cog' }}"></i>
                    </div>
                    <h4>{{ $service->title }}</h4>
                    <p style="flex:1;">{{ $service->short_description }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" class="svc-link mt-3">
                        Learn more <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                    </a>
                </div>
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
        <h2>Not sure which service fits?</h2>
        <p class="mb-5">Talk to us — we'll help you find the right solution.</p>
        <a href="{{ route('contact') }}" class="btn-white">Contact us <i class="fas fa-arrow-right" style="font-size:.75rem;"></i></a>
    </div>
</div>

@endsection
