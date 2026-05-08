@extends('layouts.frontend')
@section('title', 'Finishes')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">Our Finishes</span>
        <h1 class="page-hero-title-wide">Six finishes. One obsession with the surface.</h1>
        <p>
            Every finish is mixed, applied and polished by hand. Bespoke colours and developed in studio against samples of you space, your light and your interiors.
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Finishes</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row g-4">
            @forelse($finishes as $finish)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('finishes.show', $finish->slug) }}" class="service-grid-card">
                    <div class="service-grid-card__media">
                        @if($finish->thumbnail_url)
                            <img src="{{ $finish->thumbnail_url }}" alt="{{ $finish->title }}" loading="lazy" decoding="async">
                        @else
                            <div class="service-grid-card__placeholder" aria-hidden="true">
                                <i class="fas fa-paint-brush"></i>
                            </div>
                        @endif
                    </div>
                    <div class="service-grid-card__body">
                        <span class="service-grid-eyebrow">Finish</span>
                        <h3>{{ $finish->title }}</h3>
                        @if($finish->description)
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($finish->description), 120) }}</p>
                        @endif
                        <span class="service-grid-card__link">
                            View details <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                        </span>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5" style="color:var(--ink-light);">
                <i class="fas fa-palette fa-3x mb-3" style="color:var(--border);"></i>
                <p>No finishes have been published yet.</p>
                <a href="{{ route('contact') }}" class="btn-outline-site mt-2">Get in touch</a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<div class="cta-strip">
    <div class="container text-center">
        <span class="eyebrow">BEGIN</span>
        <h2>Not sure which finish <br />suits your space?</h2>
        <p class="mb-5">Tell us about the room and we'll prepare hand-made samples for you light.</p>
        <a href="{{ route('contact') }}" class="btn-white">Get in touch <i class="fas fa-arrow-right" style="font-size:.75rem;"></i></a>
    </div>
</div>

@endsection
