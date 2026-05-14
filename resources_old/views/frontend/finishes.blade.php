@extends('layouts.frontend')
@section('title', trim((string) ($finishesPage['page_title'] ?? '')) !== '' ? $finishesPage['page_title'] : 'Finishes')
@section('body_class', 'nav-solid page-finishes')
@section('content')

<section class="finishes-intro">
    <div class="container">
        <span class="finishes-intro__eyebrow">{{ $finishesPage['intro_eyebrow'] }}</span>
        <h1 class="finishes-intro__title">{{ $finishesPage['intro_title'] }}</h1>
        <p class="finishes-intro__desc">
            {{ $finishesPage['intro_body'] }}
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
                    : $finishesPage['card_label_fallback'];
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
                <p>{{ $finishesPage['empty_message'] }}</p>
                @if(!empty(trim($finishesPage['empty_btn_text'] ?? '')))
                <a href="{{ \App\Support\CmsOutboundHref::resolve(!empty(trim($finishesPage['empty_btn_href'] ?? '')) ? $finishesPage['empty_btn_href'] : null, 'contact') }}" class="btn-outline-site mt-2">{{ $finishesPage['empty_btn_text'] }}</a>
                @endif
            </div>
            @endforelse
        </div>

        @if($finishes instanceof \Illuminate\Contracts\Pagination\Paginator && $finishes->hasPages())
            <div class="d-flex justify-content-center mt-4">{{ $finishes->withQueryString()->links() }}</div>
        @endif
    </div>
</section>

<section class="finishes-bottom-cta">
    <div class="container text-center">
        <span class="finishes-bottom-cta__eyebrow">{{ $finishesPage['bottom_eyebrow'] }}</span>
        <h2>{{ $finishesPage['bottom_heading'] }}</h2>
        <p>{{ $finishesPage['bottom_body'] }}</p>
        @if(!empty(trim($finishesPage['bottom_btn_text'] ?? '')))
        <a href="{{ \App\Support\CmsOutboundHref::resolve(!empty(trim($finishesPage['bottom_btn_href'] ?? '')) ? $finishesPage['bottom_btn_href'] : null, 'contact') }}" class="finishes-bottom-cta__btn">{{ $finishesPage['bottom_btn_text'] }}</a>
        @endif
    </div>
</section>

@endsection
