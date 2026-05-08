@extends('layouts.frontend')
@section('title', 'Finishes')
@section('body_class', 'nav-solid page-finishes')
@section('content')

<section class="finishes-intro">
    <div class="container">
        <span class="finishes-intro__eyebrow">Our finishes</span>
        <h1 class="finishes-intro__title">Six finishes. One obsession with the surface.</h1>
        <p class="finishes-intro__desc">
            Every finish is mixed, applied and polished by hand. Bespoke colours are developed in studio against
            samples of your space, your light and your interiors.
        </p>
    </div>
</section>

<section class="finishes-grid-section">
    <div class="container">
        <div class="row finishes-grid-row">
            @forelse($finishes as $finish)
            @php
                $labelParts = collect($finish->tags ?? [])->filter()->take(3)->values();
                $finishLabel = $labelParts->isNotEmpty()
                    ? $labelParts->map(fn ($part) => \Illuminate\Support\Str::upper($part))->implode(' / ')
                    : 'Hand-crafted decorative finish';
            @endphp
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('finishes.show', $finish->slug) }}" class="finish-card">
                    <div class="finish-card__media">
                        @if($finish->thumbnail_url)
                            <img src="{{ $finish->thumbnail_url }}" alt="{{ $finish->title }}" loading="lazy" decoding="async">
                        @else
                            <div class="finish-card__placeholder" aria-hidden="true">
                            </div>
                        @endif
                    </div>
                    <div class="finish-card__body">
                        <div class="finish-card__meta">
                            <span class="finish-card__eyebrow">{{ $finishLabel }}</span>
                            <span class="finish-card__link">
                                <i class="fas fa-arrow-up-right"></i>
                            </span>
                        </div>
                        <h3>{{ $finish->title }}</h3>
                        @if($finish->description)
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($finish->description), 90) }}</p>
                        @endif
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5 finishes-grid-empty">
                <i class="fas fa-palette fa-3x mb-3"></i>
                <p>No finishes have been published yet.</p>
                <a href="{{ route('contact') }}" class="btn-outline-site mt-2">Request a consultation</a>
            </div>
            @endforelse
        </div>
    </div>
</section>

<section class="finishes-bottom-cta">
    <div class="container text-center">
        <span class="finishes-bottom-cta__eyebrow">Begin</span>
        <h2>Not sure which finish suits your space?</h2>
        <p>Tell us about the room and we&rsquo;ll prepare hand-made samples for your light.</p>
        <a href="{{ route('contact') }}" class="finishes-bottom-cta__btn">Request samples</a>
    </div>
</section>

@endsection
