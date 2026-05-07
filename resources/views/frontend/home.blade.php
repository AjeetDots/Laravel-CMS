@extends('layouts.frontend')
@section('title', 'Home')
@section('body_class', 'has-hero page-home')
@section('styles')
@php
    $__homeCss = public_path('css/home.css');
    $__homeCssV = is_file($__homeCss) ? filemtime($__homeCss) : time();
@endphp
<link href="{{ asset('css/home.css') }}?v={{ $__homeCssV }}" rel="stylesheet">
@endsection

@section('content')
@php
    $firstSlide = $sliders->first();
    $heroEyebrowDefault = 'Bespoke Ornate · Plaster Atelier';
    $heroLeadDefault = 'A London atelier crafting marble-like finishes, sculptural media walls and ornate mouldings for the world\'s most discerning interiors.';
    $sitePhone = $settings->get('site_phone');
@endphp

{{-- ── HERO ─────────────────────────────────────────────────── --}}
<section class="hero-full hero-full--premium" id="hero"
         data-hero-eyebrow-default="{{ e($heroEyebrowDefault) }}"
         data-hero-lead-default="{{ e($heroLeadDefault) }}">

    {{-- Background slides --}}
    @forelse($sliders as $i => $slide)
        <div class="hero-slide-item {{ $i === 0 ? 'active' : '' }}"
             style="background-image:url('{{ $slide->image_url }}');"
             data-title="{{ e($slide->title) }}"
             data-title-line-2="{{ e($slide->title_line_2 ?? '') }}"
             data-title-line-3="{{ e($slide->title_line_3 ?? '') }}"
             data-title-line-4="{{ e($slide->title_line_4 ?? '') }}"
             data-subtitle="{{ e($slide->subtitle) }}"
             data-lead="{{ e($slide->lead_text ?? '') }}"
             data-btn-text="{{ e($slide->button_text) }}"
             data-btn-link="{{ e($slide->button_link) }}"></div>
    @empty
        <div class="hero-full-bg"
             style="background:linear-gradient(135deg,#3b2412 0%,#2c1a0a 60%,#1a1008 100%);"></div>
    @endforelse

    <div class="hero-full-overlay"></div>

    <div class="hero-full-body container">
        <div class="row justify-content-start">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="hero-full-content hero-full-content--stagger">
                    <p class="hero-eyebrow-dots hero-eyebrow-dots--pill" id="heroEyebrow">
                        @if($firstSlide && $firstSlide->subtitle)
                            {{ $firstSlide->subtitle }}
                        @else
                            {{ $heroEyebrowDefault }}
                        @endif
                    </p>
                    <h1 class="hero-full-title hero-full-title--display {{ ($firstSlide && $firstSlide->usesHeroTitleLines()) || !$firstSlide ? 'hero-full-title--lines' : '' }}" id="heroTitle">
                        @if(!$firstSlide)
                            <span class="hero-title-line">Luxury Venetian</span>
                            <span class="hero-title-line">Plaster <span class="hero-amp">&amp;</span> Bespoke</span>
                            <span class="hero-title-line">Media Walls</span>
                        @elseif($firstSlide->usesHeroTitleLines())
                            <span class="hero-title-line">{{ $firstSlide->title }}</span>
                            @if(filled($firstSlide->title_line_2))
                                <span class="hero-title-line">{{ $firstSlide->title_line_2 }}</span>
                            @endif
                            @if(filled($firstSlide->title_line_3))
                                <span class="hero-title-line">{{ $firstSlide->title_line_3 }}</span>
                            @endif
                            @if(filled($firstSlide->title_line_4))
                                <span class="hero-title-line">{{ $firstSlide->title_line_4 }}</span>
                            @endif
                        @else
                            {{ $firstSlide->title }}
                        @endif
                    </h1>
                    <p class="hero-full-sub" id="heroLead">{{ $firstSlide && filled($firstSlide->lead_text) ? $firstSlide->lead_text : $heroLeadDefault }}</p>
                    <div class="hero-full-btns">
                        @if($firstSlide && $firstSlide->button_text)
                            <a href="{{ $firstSlide->button_link ?? route('contact') }}" class="hero-btn hero-btn--gold" id="heroBtnPrimary">
                                <span id="heroBtnText">{{ $firstSlide->button_text }}</span>
                                <i class="fa-solid fa-arrow-up-right" style="font-size:.72rem;" aria-hidden="true"></i>
                            </a>
                        @else
                            <a href="{{ route('contact') }}" class="hero-btn hero-btn--gold" id="heroBtnPrimary">
                                <span id="heroBtnText">Get a Quote</span>
                                <i class="fa-solid fa-arrow-up-right" style="font-size:.72rem;" aria-hidden="true"></i>
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="hero-btn-outline hero-btn-outline--hero">Book Consultation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide dots for multiple sliders --}}
    @if($sliders->count() > 1)
    <div class="hero-dots" role="tablist" aria-label="Hero slides">
        @foreach($sliders as $i => $s)
            <button type="button" class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}" aria-label="Slide {{ $i + 1 }}" role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}"></button>
        @endforeach
    </div>
    @endif

    <div class="hero-scroll-cue" aria-hidden="true">
        <span class="hero-scroll-label">Explore</span>
        <span class="hero-scroll-line"></span>
    </div>

</section>

{{-- ── THE ATELIER (Figma collage + quote row) ───────────────── --}}
<section class="home-atelier section-white">
    <div class="container">
        <div class="row g-5 g-xl-5 align-items-center">
            <div class="col-lg-6 order-lg-1 reveal-left">
                <div class="home-atelier-collage" aria-hidden="true">
                    <div class="home-atelier-collage__accent"></div>
                    <div class="home-atelier-collage__main">
                        <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=900&q=80"
                             alt=""
                             width="476" height="596"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--primary">
                    </div>
                    <div class="home-atelier-collage__float">
                        <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=640&q=80"
                             alt=""
                             width="291" height="254"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--inset">
                    </div>
                    <div class="home-atelier-collage__deco home-atelier-collage__deco--dots"></div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 reveal-right">
                <span class="home-atelier-kicker">The Atelier</span>
                <div class="home-atelier-headline-wrap">
                    <div class="home-atelier-headline-deco" aria-hidden="true"></div>
                    <h2 class="home-atelier-headline">
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">Surfaces that hold</span></span>
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">the <em class="home-atelier-em">light</em>, walls that</span></span>
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">hold the <em class="home-atelier-em">room</em>.</span></span>
                    </h2>
                </div>
                <p class="home-atelier-body">
                    For over two decades we have collaborated with leading interior designers,
                    architects and private clients to create plaster finishes of uncommon depth and quietude.
                    Every wall is mixed, applied and polished by hand.
                </p>
                <div class="home-atelier-actions">
                    <a href="{{ route('contact') }}" class="hero-btn hero-btn--gold home-atelier-btn">
                        Get a Quote
                        <i class="fa-solid fa-arrow-up-right ms-2" style="font-size:.72rem;" aria-hidden="true"></i>
                    </a>
                    @if($sitePhone)
                    <div class="home-atelier-phone">
                        <span class="home-atelier-phone__icon" aria-hidden="true"><i class="fa-solid fa-phone-volume"></i></span>
                        <div class="home-atelier-phone__txt">
                            <span class="home-atelier-phone__label">Booking Now</span>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $sitePhone) }}" class="home-atelier-phone__num">{{ $sitePhone }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── EXPLORE FINISHES (proposal: visual selection on home) ── --}}
<section class="commissions-section finishes-home-band">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
            <div class="reveal-left">
                <span class="eyebrow">The Finishes</span>
                <span class="section-rule" style="margin-bottom:0;"></span>
                <h2 class="home-section-title-lg">
                    Six surfaces, infinite tones.
                </h2>
            </div>
            <div class="reveal-right">
                <a href="{{ route('finishes') }}" class="btn-outline-site" style="font-size:.65rem;padding:10px 20px;">
                    All finishes <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>

        @if($finishes->count())
        <div class="commissions-grid home-finishes-grid reveal">
            @foreach($finishes->take(6) as $i => $f)
            <a href="{{ route('finishes.show', $f->slug) }}" class="commission-item @if($i === 0) is-lead @endif">
                @if($f->cover_image)
                    <img src="{{ $f->cover_image_url }}" alt="{{ $f->title }}" class="commission-img">
                @else
                    <div class="commission-placeholder"><i class="fas fa-paint-brush"></i></div>
                @endif
                <div class="commission-overlay"></div>
                <div class="commission-body">
                    <div class="commission-meta">
                        <span class="commission-category">Finish</span>
                        <p class="commission-title">{{ $f->title }}</p>
                    </div>
                    <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="commissions-grid home-finishes-grid reveal">
            @foreach(['Marmorino', 'Tadelakt', 'Metallic', 'Concrete', 'Spatulato', 'Travertino'] as $i => $name)
            <a href="{{ route('finishes') }}" class="commission-item @if($i === 0) is-lead @endif">
                <div class="commission-placeholder"><i class="fas fa-palette"></i></div>
                <div class="commission-overlay"></div>
                <div class="commission-body">
                    <div class="commission-meta">
                        <span class="commission-category">Sample</span>
                        <p class="commission-title">{{ $name }}</p>
                    </div>
                    <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ── DISCIPLINES ──────────────────────────────────────────── --}}
<section class="section section-soft disciplines-section">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7 reveal-left">
                <span class="eyebrow">Our Services</span>
                <span class="section-rule"></span>
                <h2 class="disciplines-headline">
                    Three disciplines,<br>one <em>obsession.</em>
                </h2>
            </div>
            <div class="col-lg-4 offset-lg-1 text-lg-end mt-3 mt-lg-0 reveal-right">
                <a href="{{ route('services') }}" class="btn-outline-site">
                    See all services <i class="fas fa-arrow-right ms-1" style="font-size:.7rem;"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($services->take(3) as $i => $service)
            <div class="col-md-4 reveal delay-{{ $i + 1 }}">
                <a href="{{ route('services.show', $service->slug) }}" class="disc-card">
                    <div class="disc-card-img-wrap">
                        @if($service->image)
                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="disc-card-img">
                        @else
                            <div class="disc-card-placeholder"><i class="fas fa-paint-brush"></i></div>
                        @endif
                        <div class="disc-card-overlay"></div>
                    </div>
                    <div class="disc-card-body">
                        <h4 class="disc-card-title">{{ $service->title }}</h4>
                        @if($service->short_description)
                            <p class="disc-card-desc">{{ $service->short_description }}</p>
                        @endif
                        <span class="disc-card-link">
                            Discover <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                        </span>
                    </div>
                </a>
            </div>
            @empty
            @foreach([
                ['Venetian Plaster',     'Handcrafted finishes with luminous depth and texture.',         'paint-brush'],
                ['Bespoke Media Walls',  'Custom-built entertainment walls, beautifully crafted.',        'tv'],
                ['Cornices & Mouldings', 'Ornate period and contemporary plaster profiles.',              'drafting-compass'],
            ] as $i => $svc)
            <div class="col-md-4 reveal delay-{{ $i + 1 }}">
                <div class="disc-card">
                    <div class="disc-card-img-wrap disc-card-placeholder">
                        <i class="fas fa-{{ $svc[2] }}"></i>
                    </div>
                    <div class="disc-card-body">
                        <h4 class="disc-card-title">{{ $svc[0] }}</h4>
                        <p class="disc-card-desc">{{ $svc[1] }}</p>
                        <span class="disc-card-link">
                            Discover <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>

        {{-- Show remaining services if more than 3 --}}
        @if($services->count() > 3)
        <div class="row g-4 mt-1">
            @foreach($services->skip(3) as $i => $service)
            <div class="col-md-4 reveal delay-{{ min($i+1,5) }}">
                <a href="{{ route('services.show', $service->slug) }}" class="disc-card">
                    <div class="disc-card-img-wrap">
                        @if($service->image)
                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="disc-card-img">
                        @else
                            <div class="disc-card-placeholder"><i class="fas fa-paint-brush"></i></div>
                        @endif
                        <div class="disc-card-overlay"></div>
                    </div>
                    <div class="disc-card-body">
                        <h4 class="disc-card-title">{{ $service->title }}</h4>
                        @if($service->short_description)
                            <p class="disc-card-desc">{{ $service->short_description }}</p>
                        @endif
                        <span class="disc-card-link">
                            Discover <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ── RECENT COMMISSIONS ───────────────────────────────────── --}}
@if($gallery->count())
@php $commissionItems = $gallery->take(8); @endphp
<section class="commissions-section">
    <div class="container">

        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
            <div class="reveal-left">
                <span class="eyebrow">Selected Work</span>
                <span class="section-rule" style="margin-bottom:0;"></span>
                <h2 class="home-section-title-lg" style="margin:10px 0 0;">
                    Recent commissions.
                </h2>
            </div>
            <div class="reveal-right d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('gallery') }}" class="btn-outline-site" style="font-size:.65rem;padding:10px 20px;">
                    View full gallery <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>

        <div class="commissions-grid reveal">
            @foreach($commissionItems as $i => $item)
            <a href="{{ route('gallery') }}" class="commission-item">
                @if($item->image)
                    <img src="{{ $item->image_url }}"
                         alt="{{ $item->title ?? 'Commission' }}"
                         class="commission-img">
                @else
                    <div class="commission-placeholder"><i class="fas fa-paint-brush"></i></div>
                @endif
                <div class="commission-overlay"></div>
                <div class="commission-body">
                    <div class="commission-meta">
                        @if($item->category)
                            <span class="commission-category">{{ is_object($item->category) ? $item->category->name : $item->category }}</span>
                        @endif
                        <p class="commission-title">{{ $item->title ?? 'Untitled' }}</p>
                    </div>
                    <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif

{{-- ── WHY BESPOKE ORNATE ───────────────────────────────────── --}}
<section class="home-why section-soft">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-end mb-5">
            <div class="col-lg-5 reveal-left">
                <span class="eyebrow">Why Bespoke Ornate</span>
                <span class="section-rule"></span>
                <h2 class="home-section-title-md mb-0">A studio defined by its hands.</h2>
            </div>
            <div class="col-lg-6 offset-lg-1 reveal-right">
                <p class="home-why-lead mb-0">
                    Each project is led by master artisans trained in traditional Italian techniques
                    and refined for the demands of contemporary architecture.
                </p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-xl-3 reveal delay-1">
                <div class="home-why-card">
                    <span class="home-why-card__icon" aria-hidden="true"><i class="fa-solid fa-award"></i></span>
                    <h3 class="home-why-card__title">Master Craftsmanship</h3>
                    <p class="home-why-card__desc">Every surface mixed, applied and polished by hand.</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 reveal delay-2">
                <div class="home-why-card">
                    <span class="home-why-card__icon" aria-hidden="true"><i class="fa-solid fa-palette"></i></span>
                    <h3 class="home-why-card__title">Bespoke by Design</h3>
                    <p class="home-why-card__desc">Custom tones, textures and profiles, never off-the-shelf.</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 reveal delay-3">
                <div class="home-why-card">
                    <span class="home-why-card__icon" aria-hidden="true"><i class="fa-solid fa-clapperboard"></i></span>
                    <h3 class="home-why-card__title">Trusted by Productions</h3>
                    <p class="home-why-card__desc">Selected for major film, TV and editorial productions.</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-3 reveal delay-4">
                <div class="home-why-card">
                    <span class="home-why-card__icon" aria-hidden="true"><i class="fa-solid fa-leaf"></i></span>
                    <h3 class="home-why-card__title">Considered Materials</h3>
                    <p class="home-why-card__desc">Lime-based, breathable, low-VOC formulations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── OUR PROCESS ──────────────────────────────────────────── --}}
<section class="home-process section-white">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="eyebrow d-inline-block">Our Process</span>
            <h2 class="home-process-title mt-3 mb-0">From first conversation to final polish.</h2>
        </div>
        <ol class="home-process-steps list-unstyled mb-0">
            <li class="home-process-step reveal delay-1">
                <span class="home-process-step__num font-serif">01</span>
                <h3 class="home-process-step__title">Consultation</h3>
                <p class="home-process-step__desc">We visit your space, listen and study the light.</p>
            </li>
            <li class="home-process-step reveal delay-2">
                <span class="home-process-step__num font-serif">02</span>
                <h3 class="home-process-step__title">Design</h3>
                <p class="home-process-step__desc">Bespoke samples, tones and textures developed in studio.</p>
            </li>
            <li class="home-process-step reveal delay-3">
                <span class="home-process-step__num font-serif">03</span>
                <h3 class="home-process-step__title">Quote</h3>
                <p class="home-process-step__desc">A clear, transparent proposal with timelines.</p>
            </li>
            <li class="home-process-step reveal delay-4">
                <span class="home-process-step__num font-serif">04</span>
                <h3 class="home-process-step__title">Execution</h3>
                <p class="home-process-step__desc">Hand-applied by our master artisans on site.</p>
            </li>
        </ol>
    </div>
</section>

{{-- ── TESTIMONIALS — Figma 50/50 split (photo | brown panel) ── --}}
@if($testimonials->count())
<section class="testi-split-section reveal" aria-labelledby="testi-split-heading">
    <h2 id="testi-split-heading" class="visually-hidden">Customer reviews</h2>
    <div class="testi-split-shell">
        <div class="testi-track testi-split-track" id="testiTrack">
            @foreach($testimonials as $i => $t)
                @php
                    $msg = trim((string) $t->message);
                    $sentences = preg_split('/(?<=[.!?])\s+/u', $msg, -1, PREG_SPLIT_NO_EMPTY);
                    $sentences = array_values(array_filter(array_map('trim', $sentences)));
                    // Avoid repeating the opening on both sides: multi-sentence → left = first line, right = rest only.
                    if (count($sentences) >= 2) {
                        $quoteLeft = $sentences[0];
                        if (Str::length($quoteLeft) > 220) {
                            $quoteLeft = Str::limit($quoteLeft, 180, '…');
                        }
                        $msgRight = trim(implode(' ', array_slice($sentences, 1)));
                    } else {
                        $quoteLeft = '';
                        $msgRight = $msg;
                    }
                    $photoUrl = $t->client_image ? $t->client_image_url : 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=1200&q=85';
                    $leftTitle = $t->client_company ?: $t->client_name;
                    $panelRole = collect([$t->client_position, $t->client_company])->filter()->implode(', ');
                @endphp
            <article class="testi-slide testi-split-slide">
                <div class="testi-split">
                    <div class="testi-split__photo"
                         style="background-image: url('{{ $photoUrl }}');">
                        <div class="testi-split__photo-scrim" aria-hidden="true"></div>
                        <div class="testi-split__photo-inner{{ $quoteLeft === '' ? ' testi-split__photo-inner--attrib-only' : '' }}">
                            @if($quoteLeft !== '')
                            <p class="testi-split__quote-short">&ldquo;{{ $quoteLeft }}&rdquo;</p>
                            @endif
                            <div class="testi-split__photo-attrib">
                                <p class="testi-split__photo-name">{{ $leftTitle }}</p>
                                @if($t->client_position)
                                    <p class="testi-split__photo-role">{{ strtoupper($t->client_position) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="testi-split__panel">
                        <div class="testi-split__panel-deco" aria-hidden="true"></div>
                        <p class="testi-split__kicker">Customer reviews</p>
                        <blockquote class="testi-split__quote-full">
                            <p>&ldquo;{{ $msgRight }}&rdquo;</p>
                        </blockquote>
                        <p class="testi-split__panel-name">{{ $t->client_name }}</p>
                        @if($panelRole !== '')
                            <p class="testi-split__panel-role">{{ strtoupper($panelRole) }}</p>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <div class="testi-split__seam-badge" aria-hidden="true">
            <span class="testi-split__seam-icon">&ldquo;</span>
        </div>

        @if($testimonials->count() > 1)
        <button type="button" class="testi-split-nav testi-split-nav--prev" id="testiPrev" aria-label="Previous testimonial">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button type="button" class="testi-split-nav testi-split-nav--next" id="testiNext" aria-label="Next testimonial">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="testi-split__dots-wrap">
            {{-- Indicators: Figma horizontal pills (.testi-dot--pill), not round dots --}}
            <div class="testi-dots testi-dots--split" id="testiDots" role="tablist" aria-label="Choose testimonial">
                @foreach($testimonials as $i => $t)
                <button type="button" class="testi-dot testi-dot--pill {{ $i === 0 ? 'active' : '' }}" data-idx="{{ $i }}" aria-label="Testimonial {{ $i + 1 }}" role="tab"></button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

{{-- ── BEGIN A PROJECT (full-bleed) ─────────────────────────── --}}
<section class="home-begin-cta position-relative">
    <div class="home-begin-cta__bg" style="background-image:url('https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80');"></div>
    <div class="home-begin-cta__overlay"></div>
    <div class="container position-relative text-center home-begin-cta__inner">
        <span class="home-begin-cta__eyebrow">Begin a Project</span>
        <h2 class="home-begin-cta__title">Transform your space into a quiet masterpiece.</h2>
        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
            <a href="{{ route('contact') }}" class="hero-btn hero-btn--gold">Schedule a consultation</a>
            <a href="{{ route('portfolio') }}" class="hero-btn-outline hero-btn-outline--hero home-begin-cta__ghost">View portfolio</a>
        </div>
    </div>
</section>

{{-- ── CONTACT (homepage band — mirrors Figma) ──────────────── --}}
<section class="home-contact-band section-white" id="home-contact">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="eyebrow">Contact Us</span>
            <h2 class="home-contact-band__title mt-2 mb-0">How we can help?</h2>
        </div>
        <div class="row g-4 g-xl-5 align-items-stretch">
            <div class="col-lg-5 reveal-left d-none d-lg-block">
                <div class="home-contact-band__visual rounded overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=900&q=80"
                         alt=""
                         class="w-100 h-100 object-fit-cover rounded"
                         loading="lazy" decoding="async"
                         style="min-height:340px;">
                </div>
            </div>
            <div class="col-lg-7 reveal-right">
                <div class="home-contact-panel">
                    @if($errors->any())
                    <div class="alert alert-danger mb-3 small">Please check the form and try again.</div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="home-contact-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small text-uppercase text-muted mb-1" for="home_contact_name">Your Name</label>
                            <input type="text" name="name" id="home_contact_name" required
                                   value="{{ old('name') }}"
                                   class="form-control home-contact-input @error('name') is-invalid @enderror"
                                   autocomplete="name">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-uppercase text-muted mb-1" for="home_contact_email">Email</label>
                            <input type="email" name="email" id="home_contact_email" required
                                   value="{{ old('email') }}"
                                   class="form-control home-contact-input @error('email') is-invalid @enderror"
                                   autocomplete="email">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-uppercase text-muted mb-1" for="home_contact_phone">Phone <span class="fw-normal">(Optional)</span></label>
                            <input type="text" name="phone" id="home_contact_phone"
                                   value="{{ old('phone') }}"
                                   class="form-control home-contact-input @error('phone') is-invalid @enderror"
                                   autocomplete="tel">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <input type="hidden" name="subject" value="Website enquiry (home)">
                        <div class="mb-4">
                            <label class="form-label small text-uppercase text-muted mb-1" for="home_contact_message">Tell us about your space</label>
                            <textarea name="message" id="home_contact_message" rows="4" required minlength="10"
                                      class="form-control home-contact-input @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="hero-btn hero-btn--gold w-100 justify-content-center">
                            Send message
                            <i class="fa-solid fa-arrow-up-right ms-2" style="font-size:.72rem;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── BRANDS ───────────────────────────────────────────────── --}}
@if($brands->count())
<section class="brands-strip" aria-labelledby="brands-strip-title">
    <div class="container">
        <header class="brands-strip__head">
            <span class="brands-strip__rule" aria-hidden="true"></span>
            <div class="brands-strip__title-block">
                <span class="brands-strip__kicker">Partners &amp; collaborators</span>
                <h2 class="brands-strip__title" id="brands-strip-title">Trusted by leading names</h2>
            </div>
            <span class="brands-strip__rule" aria-hidden="true"></span>
        </header>
    </div>
    @php
        /* Enough repeated strips to exceed viewport width so the row never shows empty space before the loop repeats */
        $brandMarqueeSegments = 8;
    @endphp
    <div class="brands-strip__marquee" role="presentation">
        <div class="brands-slider-wrap">
            <div class="brand-track" style="--brand-segments: {{ $brandMarqueeSegments }}">
                @foreach(range(1, $brandMarqueeSegments) as $seg)
                <div class="brand-track__segment" @if($seg > 1) aria-hidden="true" @endif>
                    @foreach($brands as $brand)
                    <div class="brand-logo-item">
                        <div class="brand-logo-item__frame">
                            @if($brand->logo)
                                <img class="brand-logo-img" src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" loading="lazy" decoding="async">
                            @else
                                <span class="brand-placeholder">{{ $brand->name }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── BLOG PREVIEW ─────────────────────────────────────────── --}}
@if($blogPosts->count())
<section class="section section-white home-journal">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-12 col-lg-7 reveal-left">
                <span class="eyebrow">Our Blog</span>
                <span class="section-rule"></span>
                <h2 class="home-blog-heading mb-0">
                    Latest News
                </h2>
            </div>
            <div class="col-12 col-lg-3 offset-lg-2 text-lg-end mt-3 mt-lg-0 reveal-right">
                <a href="{{ route('blog.index') }}" class="btn-outline-site">
                    All articles <i class="fas fa-arrow-right ms-1" style="font-size:.75rem;"></i>
                </a>
            </div>
        </div>
        <div class="home-blog-scroller" id="homeBlogScroller">
            <div class="row g-4 flex-nowrap flex-lg-wrap pb-1 home-blog-row">
            @foreach($blogPosts as $i => $post)
            <div class="col-10 col-md-6 col-lg-4 flex-shrink-0 flex-lg-shrink-1 reveal delay-{{ $i + 1 }}">
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card d-block h-100">
                    <div class="blog-card-img-wrap">
                        @if($post->image)
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="blog-card-img">
                        @else
                            <div class="blog-card-img-placeholder"><i class="fas fa-feather-alt"></i></div>
                        @endif
                        @if($post->category)<span class="blog-badge">{{ $post->category }}</span>@endif
                    </div>
                    <div class="blog-card-body">
                        <h3 class="blog-card-title">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 90) }}</p>
                        @endif
                        <div class="blog-card-meta">
                            @if($post->author)<span><i class="fas fa-user me-1"></i>{{ $post->author }}</span>@endif
                            <span><i class="far fa-clock me-1"></i>{{ $post->reading_time }}</span>
                        </div>
                        <span class="blog-read-more">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            @endforeach
            </div>
        </div>
        @if($blogPosts->count() > 1)
        <div class="home-blog-dots d-flex d-lg-none justify-content-center gap-2 mt-3" id="homeBlogDots" aria-hidden="true"></div>
        @endif
    </div>
</section>
@endif

@endsection

@section('scripts')
<script>
// Multi-slide hero
(function () {
    var slides  = document.querySelectorAll('.hero-slide-item');
    var dots    = document.querySelectorAll('.hero-dot');
    var content = document.querySelector('.hero-full-content');
    var heroEl  = document.getElementById('hero');
    var eyebrowDefault = heroEl && heroEl.dataset.heroEyebrowDefault ? heroEl.dataset.heroEyebrowDefault : '';
    var eyebrow = document.getElementById('heroEyebrow');
    var title   = document.getElementById('heroTitle');
    var leadEl  = document.getElementById('heroLead');
    var btnText = document.getElementById('heroBtnText');
    var btnEl   = document.getElementById('heroBtnPrimary');
    var leadDefault = heroEl && heroEl.dataset.heroLeadDefault ? heroEl.dataset.heroLeadDefault : '';

    if (slides.length < 2) return;
    var current = 0, timer;

    function renderHeroTitleFromSlide(titleEl, slide) {
        if (!titleEl || !slide) return;
        var main = slide.getAttribute('data-title') || '';
        var l2 = slide.getAttribute('data-title-line-2') || '';
        var l3 = slide.getAttribute('data-title-line-3') || '';
        var l4 = slide.getAttribute('data-title-line-4') || '';
        var useLines = l2 !== '' || l3 !== '' || l4 !== '';
        if (!useLines) {
            titleEl.textContent = main;
            titleEl.classList.remove('hero-full-title--lines');
            return;
        }
        titleEl.classList.add('hero-full-title--lines');
        titleEl.innerHTML = '';
        [main, l2, l3, l4].forEach(function (text) {
            if (text === '') return;
            var span = document.createElement('span');
            span.className = 'hero-title-line';
            span.textContent = text;
            titleEl.appendChild(span);
        });
    }

    function updateText(slide) {
        if (!content) return;
        content.classList.add('fading');
        setTimeout(function () {
            var sub = slide.getAttribute('data-subtitle');
            if (eyebrow) eyebrow.textContent = (sub && sub.length) ? sub : eyebrowDefault;
            if (title) renderHeroTitleFromSlide(title, slide);
            var bt = slide.getAttribute('data-btn-text');
            if (btnText && bt) btnText.textContent = bt;
            var bh = slide.getAttribute('data-btn-link');
            if (btnEl && bh) btnEl.href = bh;
            var ld = slide.getAttribute('data-lead');
            if (leadEl) leadEl.textContent = (ld && ld.length) ? ld : leadDefault;
            content.classList.remove('fading');
        }, 350);
    }

    function goTo(n) {
        slides[current].classList.remove('active');
        if (dots[current]) {
            dots[current].classList.remove('active');
            dots[current].setAttribute('aria-selected', 'false');
        }
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        if (dots[current]) {
            dots[current].classList.add('active');
            dots[current].setAttribute('aria-selected', 'true');
        }
        updateText(slides[current]);
    }
    function start() { timer = setInterval(function () { goTo(current + 1); }, 6000); }
    function reset()  { clearInterval(timer); start(); }

    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () { goTo(i); reset(); });
    });
    start();
})();

// Testimonial full-width slider
(function () {
    var track  = document.getElementById('testiTrack');
    var btnPrev = document.getElementById('testiPrev');
    var btnNext = document.getElementById('testiNext');
    var dotsWrap = document.getElementById('testiDots');
    var dotEls  = dotsWrap ? dotsWrap.querySelectorAll('.testi-dot--pill') : [];
    if (!track) return;
    var slides = track.querySelectorAll('.testi-slide');
    if (slides.length < 2) return;

    var current = 0, timer;

    function goTo(n) {
        current = ((n % slides.length) + slides.length) % slides.length;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';
        dotEls.forEach(function (d, i) { d.classList.toggle('active', i === current); });
    }
    function start() { timer = setInterval(function () { goTo(current + 1); }, 5000); }
    function reset() { clearInterval(timer); start(); }

    btnPrev && btnPrev.addEventListener('click', function () { goTo(current - 1); reset(); });
    btnNext && btnNext.addEventListener('click', function () { goTo(current + 1); reset(); });
    dotEls.forEach(function (d, i) {
        d.addEventListener('click', function () { goTo(i); reset(); });
    });
    start();
})();

// Blog strip — scroll dots (mobile)
(function () {
    var sc = document.getElementById('homeBlogScroller');
    var dotsWrap = document.getElementById('homeBlogDots');
    if (!sc || !dotsWrap) return;
    var row = sc.querySelector('.home-blog-row');
    if (!row || row.children.length < 2) return;
    dotsWrap.innerHTML = '';
    var i;
    for (i = 0; i < row.children.length; i++) {
        var dot = document.createElement('span');
        dot.className = 'home-blog-dot' + (i === 0 ? ' active' : '');
        dotsWrap.appendChild(dot);
    }
    var dots = dotsWrap.querySelectorAll('.home-blog-dot');
    var measureStep = function () {
        var first = row.children[0];
        if (!first) return 320;
        var gap = parseFloat(window.getComputedStyle(row).gap) || 24;
        return first.getBoundingClientRect().width + gap;
    };
    sc.addEventListener(
        'scroll',
        function () {
            var step = measureStep();
            var idx = Math.min(dots.length - 1, Math.max(0, Math.round(sc.scrollLeft / step)));
            dots.forEach(function (d, j) {
                d.classList.toggle('active', j === idx);
            });
        },
        { passive: true }
    );
})();
</script>
@endsection
