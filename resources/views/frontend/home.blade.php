@extends('layouts.frontend')
@section('title', 'Home')
@section('body_class', 'has-hero')
@section('styles')
<style>
/* ═══════════════════════════════════════
   FULL-WIDTH HERO
═══════════════════════════════════════ */
.hero-full {
    position: relative;
    height: 100vh;          /* full viewport — nav is fixed so no offset needed */
    min-height: 600px;
    overflow: hidden;
    display: flex;
    align-items: center;
}
.hero-full-bg {
    position: absolute; inset: 0;
    background-size: cover;
    background-position: center;
    transform: scale(1.04);
    animation: heroZoom 14s ease-out forwards;
}
@keyframes heroZoom {
    from { transform: scale(1.04); }
    to   { transform: scale(1); }
}
.hero-full-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(
        to right,
        rgba(20,12,6,.72) 0%,
        rgba(20,12,6,.40) 55%,
        rgba(20,12,6,.08) 100%
    );
}
.hero-full-body {
    position: relative; z-index: 2;
    width: 100%;
}
.hero-full-content {
    padding: 20px 0;
}
.hero-eyebrow-dots {
    font-size: clamp(.82rem, 1.15vw, 1rem);
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: none;
    line-height: 1.55;
    color: rgba(255, 245, 230, .92);
    margin-bottom: 20px;
    display: block;
    max-width: 38rem;
    opacity: .95;
}
.hero-eyebrow-dots::before {
    content: '';
    display: block;
    width: 42px;
    height: 2px;
    background: linear-gradient(90deg, var(--gold), var(--wine));
    margin-bottom: 14px;
    border-radius: 2px;
}
.hero-full-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2.4rem, 4.5vw, 3.8rem);
    font-weight: 700; color: #fff;
    line-height: 1.13; margin-bottom: 22px;
    letter-spacing: -.5px;
}
.hero-full-sub {
    font-size: .97rem; color: rgba(255,255,255,.75);
    line-height: 1.8; max-width: 420px;
    margin-bottom: 36px;
}
.hero-full-btns {
    display: flex; gap: 14px; flex-wrap: wrap;
}
.hero-dots {
    position: absolute; bottom: 30px; right: 40px;
    z-index: 10; display: flex; gap: 8px; align-items: center;
}
.hero-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,.35); border: none; cursor: pointer;
    transition: background .3s, transform .3s; padding: 0;
}
.hero-dot.active {
    background: var(--gold); transform: scale(1.3);
}
/* multi-slide support */
.hero-slide-item {
    position: absolute; inset: 0;
    background-size: cover; background-position: center;
    opacity: 0; transition: opacity .9s ease;
}
.hero-slide-item.active { opacity: 1; }

/* text fade on slide change */
.hero-full-content { transition: opacity .4s ease; }
.hero-full-content.fading { opacity: 0; }

@media (max-width: 767px) {
    .hero-full { height: 90vh; min-height: 480px; }
    .hero-full-body .row { justify-content: flex-start !important; }
    .hero-full-body .col-lg-6 { flex: 0 0 100%; max-width: 100%; padding: 0 20px; }
    .hero-full-title { font-size: 2.2rem; }
}

/* ═══════════════════════════════════════
   INTRO SECTION
═══════════════════════════════════════ */
.intro-section { padding: 90px 0; }
.intro-headline {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.8rem, 3.5vw, 2.7rem);
    font-weight: 700; color: var(--ink);
    line-height: 1.25; margin-bottom: 0;
    letter-spacing: -.3px;
}
.intro-body {
    font-size: 1rem; color: var(--ink-light);
    line-height: 1.9; margin: 0;
    max-width: 500px;
}

/* ═══════════════════════════════════════
   DISCIPLINES SECTION
═══════════════════════════════════════ */
.disciplines-headline {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2rem, 3.8vw, 3rem);
    font-weight: 700; color: var(--ink);
    line-height: 1.2; margin-bottom: 0;
    letter-spacing: -.3px;
}
.disciplines-headline em {
    font-style: italic; color: var(--wine);
}

.disc-card {
    display: block; color: var(--ink); text-decoration: none;
    transition: transform .35s ease;
}
.disc-card:hover { transform: translateY(-4px); color: var(--ink); }
.disc-card-img-wrap {
    position: relative; overflow: hidden;
    aspect-ratio: 3/4; background: var(--cream-dark);
    margin-bottom: 0;
}
.disc-card-placeholder {
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem; color: var(--border);
}
.disc-card-img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .6s ease;
    display: block;
}
.disc-card:hover .disc-card-img { transform: scale(1.06); }
.disc-card-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(20,10,6,.55) 0%, transparent 55%);
    transition: background .4s;
}
.disc-card:hover .disc-card-overlay {
    background: linear-gradient(to top, rgba(160,88,56,.6) 0%, rgba(160,88,56,.15) 60%, transparent 100%);
}
.disc-card-body {
    padding: 20px 4px 0;
}
.disc-card-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.15rem; font-weight: 600;
    color: var(--ink); margin-bottom: 8px;
    letter-spacing: 0;
}
.disc-card-desc {
    font-size: .87rem; color: var(--ink-light);
    line-height: 1.75; margin-bottom: 10px;
}
.disc-card-link {
    font-size: .7rem; font-weight: 700; letter-spacing: 2px;
    text-transform: uppercase; color: var(--wine);
    display: inline-flex; align-items: center; gap: 6px;
    border-bottom: 1px solid transparent;
    transition: border-color .2s, gap .2s;
}
.disc-card:hover .disc-card-link {
    border-bottom-color: var(--wine); gap: 10px;
}

/* ═══════════════════════════════════════
   RECENT COMMISSIONS GRID
═══════════════════════════════════════ */
.commissions-section { background: var(--cream-dark); padding: 90px 0; }

.commissions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: 280px;
    gap: 8px;
}
/* First item is tall — spans 2 rows */
.commission-item:first-child {
    grid-row: span 2;
}

.commission-item {
    position: relative; overflow: hidden; cursor: pointer;
    background: var(--ink);
    display: block; color: inherit; text-decoration: none;
}
.commission-img {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .65s cubic-bezier(.25,.46,.45,.94);
}
.commission-item:hover .commission-img { transform: scale(1.06); }
.commission-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top,
        rgba(20,10,5,.82) 0%,
        rgba(20,10,5,.30) 45%,
        transparent 100%);
    transition: background .4s;
}
.commission-item:hover .commission-overlay {
    background: linear-gradient(to top,
        rgba(160,88,56,.82) 0%,
        rgba(160,88,56,.25) 55%,
        transparent 100%);
}
.commission-body {
    position: absolute; bottom: 0; left: 0; right: 0;
    padding: 22px 20px;
    display: flex; align-items: flex-end; justify-content: space-between;
    gap: 12px;
}
.commission-meta {
    flex: 1; min-width: 0;
}
.commission-category {
    display: block;
    font-size: .58rem; font-weight: 700; letter-spacing: 2.5px;
    text-transform: uppercase; color: var(--gold);
    margin-bottom: 5px; opacity: .85;
}
.commission-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.05rem; font-weight: 600; color: #fff;
    line-height: 1.25; margin: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
/* tall card gets slightly bigger title */
.commission-item:first-child .commission-title { font-size: 1.2rem; }
.commission-arrow {
    flex-shrink: 0;
    width: 34px; height: 34px; border-radius: 50%;
    border: 1px solid rgba(255,255,255,.35);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.7); font-size: .75rem;
    transition: background .25s, border-color .25s, color .25s, transform .25s;
}
.commission-item:hover .commission-arrow {
    background: var(--gold); border-color: var(--gold);
    color: var(--ink); transform: rotate(45deg);
}

/* placeholder (no image) */
.commission-placeholder {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: rgba(255,255,255,.15);
}

/* responsive */
@media (max-width: 991px) {
    .commissions-grid {
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 240px;
    }
    .commission-item:first-child { grid-row: span 2; }
}
@media (max-width: 575px) {
    .commissions-grid {
        grid-template-columns: 1fr;
        grid-auto-rows: 220px;
    }
    .commission-item:first-child { grid-row: span 1; }
    .commissions-section { padding: 60px 0; }
}
</style>
@endsection

@section('content')
@php
    $firstSlide = $sliders->first();
    $heroEyebrowDefault = 'Years of experience across web, mobile, design and cloud technologies.';
@endphp

{{-- ── HERO ─────────────────────────────────────────────────── --}}
<section class="hero-full" id="hero" data-hero-eyebrow-default="{{ e($heroEyebrowDefault) }}">

    {{-- Background slides --}}
    @forelse($sliders as $i => $slide)
        <div class="hero-slide-item {{ $i === 0 ? 'active' : '' }}"
             style="background-image:url('{{ $slide->image_url }}');"
             data-title="{{ $slide->title }}"
             data-subtitle="{{ e($slide->subtitle) }}"
             data-btn-text="{{ $slide->button_text }}"
             data-btn-link="{{ $slide->button_link }}"></div>
    @empty
        <div class="hero-full-bg"
             style="background:linear-gradient(135deg,#3b2412 0%,#2c1a0a 60%,#1a1008 100%);"></div>
    @endforelse

    <div class="hero-full-overlay"></div>

    <div class="hero-full-body container">
        <div class="row justify-content-start">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="hero-full-content">
                    <p class="hero-eyebrow-dots" id="heroEyebrow">
                        @if($firstSlide && $firstSlide->subtitle)
                            {{ $firstSlide->subtitle }}
                        @else
                            {{ $heroEyebrowDefault }}
                        @endif
                    </p>
                    <h1 class="hero-full-title" id="heroTitle">
                        @if($firstSlide && $firstSlide->title)
                            {{ $firstSlide->title }}
                        @else
                            Luxury Venetian Plaster &amp; Bespoke Media Walls
                        @endif
                    </h1>
                    <div class="hero-full-btns">
                        @if($firstSlide && $firstSlide->button_text)
                            <a href="{{ $firstSlide->button_link ?? route('services') }}" class="hero-btn" id="heroBtnPrimary">
                                <span id="heroBtnText">{{ $firstSlide->button_text }}</span>
                                <i class="fas fa-arrow-right" style="font-size:.72rem;"></i>
                            </a>
                        @else
                            <a href="{{ route('services') }}" class="hero-btn" id="heroBtnPrimary">
                                <span id="heroBtnText">Explore Work</span>
                                <i class="fas fa-arrow-right" style="font-size:.72rem;"></i>
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="hero-btn-outline">Free Consultation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide dots for multiple sliders --}}
    @if($sliders->count() > 1)
    <div class="hero-dots">
        @foreach($sliders as $i => $s)
            <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif

</section>

{{-- ── INTRO ────────────────────────────────────────────────── --}}
<section class="intro-section section-white">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5 reveal-left">
                <span class="eyebrow">Our Philosophy</span>
                <span class="section-rule"></span>
                <h2 class="intro-headline">
                    Surfaces that hold<br>the light, walls that<br>hold the room.
                </h2>
            </div>
            <div class="col-lg-6 offset-lg-1 reveal-right">
                <p class="intro-body">
                    For over two decades we've been collaborating with leading interior designers,
                    architects and private clients to create plaster finishes that breathe depth
                    and stillness. Every wall is hand-set, applied and polished by our specialist
                    artisans — never rushed, always bespoke.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ── DISCIPLINES ──────────────────────────────────────────── --}}
<section class="section section-soft">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7 reveal-left">
                <span class="eyebrow">What we do</span>
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
                            <p class="disc-card-desc">{{ Str::limit($service->short_description, 90) }}</p>
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
                            <p class="disc-card-desc">{{ Str::limit($service->short_description, 90) }}</p>
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

{{-- ── STATS ────────────────────────────────────────────────── --}}
<div class="stats-band">
    <div class="container">
        <div class="d-flex align-items-center justify-content-around flex-wrap gap-4">
            <div class="stat-item reveal">
                <span class="stat-num"><span data-count="15" data-suffix="+">15+</span></span>
                <span class="stat-lbl">Years of experience</span>
            </div>
            <div class="stat-sep d-none d-md-block" style="height:60px;"></div>
            <div class="stat-item reveal delay-1">
                <span class="stat-num"><span data-count="850" data-suffix="+">850+</span></span>
                <span class="stat-lbl">Projects completed</span>
            </div>
            <div class="stat-sep d-none d-md-block" style="height:60px;"></div>
            <div class="stat-item reveal delay-2">
                <span class="stat-num"><span data-count="100" data-suffix="%">100%</span></span>
                <span class="stat-lbl">Bespoke &amp; handcrafted</span>
            </div>
            <div class="stat-sep d-none d-md-block" style="height:60px;"></div>
            <div class="stat-item reveal delay-3">
                <span class="stat-num"><span data-count="12">12</span></span>
                <span class="stat-lbl">Master artisans</span>
            </div>
        </div>
    </div>
</div>

{{-- ── RECENT COMMISSIONS ───────────────────────────────────── --}}
@php
    $defaultCommissions = collect([
        ['title'=>'Mayfair Residence',      'category'=>'Venetian Plaster', 'img'=>null],
        ['title'=>'Kensington Media Wall',  'category'=>'Media Walls',      'img'=>null],
        ['title'=>'Chelsea Townhouse',      'category'=>'Venetian Plaster', 'img'=>null],
        ['title'=>'Notting Hill Suite',     'category'=>'Venetian Plaster', 'img'=>null],
        ['title'=>'Private Screening Room', 'category'=>'Media Walls',      'img'=>null],
        ['title'=>'Belgravia Drawing Room', 'category'=>'Cornices',         'img'=>null],
    ]);
    $commissionItems = $gallery->count() ? $gallery->take(6) : collect();
@endphp
<section class="commissions-section">
    <div class="container">

        {{-- Header --}}
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
            <div class="reveal-left">
                <span class="eyebrow">Selected work</span>
                <span class="section-rule" style="margin-bottom:0;"></span>
                <h2 style="font-family:'Playfair Display',serif;font-size:clamp(2rem,4vw,3rem);font-weight:700;letter-spacing:-.3px;margin:10px 0 0;color:var(--ink);">
                    Recent commissions.
                </h2>
            </div>
            <div class="reveal-right">
                <a href="{{ route('gallery') }}" class="btn-outline-site" style="font-size:.65rem;padding:10px 20px;">
                    Full Portfolio <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>

        {{-- Asymmetric grid --}}
        <div class="commissions-grid reveal">
            @if($commissionItems->count())
                {{-- Real gallery items --}}
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
                            @if(!empty($item->category))
                                <span class="commission-category">{{ $item->category }}</span>
                            @endif
                            <p class="commission-title">{{ $item->title ?? 'Untitled' }}</p>
                        </div>
                        <div class="commission-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
                @endforeach
                {{-- Pad to 6 with placeholders if fewer items --}}
                @for($p = $commissionItems->count(); $p < 6; $p++)
                <div class="commission-item">
                    <div class="commission-placeholder"><i class="fas fa-image"></i></div>
                    <div class="commission-overlay"></div>
                    <div class="commission-body">
                        <div class="commission-meta">
                            <span class="commission-category">Our Work</span>
                            <p class="commission-title">Project {{ $p + 1 }}</p>
                        </div>
                        <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
                @endfor
            @else
                {{-- Fallback placeholder commissions --}}
                @foreach($defaultCommissions as $i => $c)
                <div class="commission-item" style="background:{{ ['#2c2016','#1e1a14','#251d15','#1a1510','#221a12','#2a2018'][$i] }};">
                    <div class="commission-overlay" style="background:linear-gradient(to top,rgba(20,10,5,.75) 0%,transparent 55%);"></div>
                    <div class="commission-body">
                        <div class="commission-meta">
                            <span class="commission-category">{{ $c['category'] }}</span>
                            <p class="commission-title">{{ $c['title'] }}</p>
                        </div>
                        <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

    </div>
</section>

{{-- ── TESTIMONIALS ─────────────────────────────────────────── --}}
@if($testimonials->count())
<section class="section section-soft">
    <div class="container">
        <div class="text-center mb-5 reveal" style="max-width:520px;margin-left:auto;margin-right:auto;">
            <span class="eyebrow">Client voices</span>
            <span class="section-rule centered"></span>
            <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.9rem,4vw,2.8rem);font-weight:700;letter-spacing:-.3px;">
                Trusted by discerning<br>clients worldwide.
            </h2>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $i => $t)
            <div class="col-md-6 col-lg-4 reveal delay-{{ min($i+1,5) }}">
                <div class="testi-card">
                    <div class="testi-stars">
                        @for($s = 1; $s <= ($t->rating ?? 5); $s++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                    <p class="testi-quote">"{{ $t->message }}"</p>
                    <div class="testi-author">
                        <div class="testi-avatar">
                            @if($t->client_image)
                                <img src="{{ $t->client_image_url }}" alt="{{ $t->client_name }}">
                            @else
                                {{ strtoupper(substr($t->client_name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="testi-name">{{ $t->client_name }}</div>
                            <div class="testi-role">
                                {{ $t->client_position }}{{ $t->client_company ? ', ' . $t->client_company : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── BRANDS ───────────────────────────────────────────────── --}}
@if($brands->count())
<section class="section-sm" style="background:var(--cream);border-top:1px solid var(--border);border-bottom:1px solid var(--border);overflow:hidden;">
    <div class="container">
        <p style="text-align:center;font-size:.68rem;font-weight:700;letter-spacing:3.5px;text-transform:uppercase;color:var(--ink-light);margin-bottom:36px;">
            Trusted by leading names
        </p>
    </div>
    <div style="overflow:hidden;">
        <div class="brand-track">
            @foreach($brands->concat($brands) as $brand)
            <div class="brand-logo-item">
                @if($brand->logo)
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}">
                @else
                    <div class="brand-placeholder">{{ $brand->name }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── BLOG PREVIEW ─────────────────────────────────────────── --}}
@if($blogPosts->count())
<section class="section section-white">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7 reveal-left">
                <span class="eyebrow">Our Journal</span>
                <span class="section-rule"></span>
                <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.9rem,4vw,2.8rem);font-weight:700;letter-spacing:-.3px;margin-bottom:0;">
                    Insights &amp; stories<br>from the studio.
                </h2>
            </div>
            <div class="col-lg-3 offset-lg-2 text-lg-end mt-3 mt-lg-0 reveal-right">
                <a href="{{ route('blog.index') }}" class="btn-outline-site">
                    All articles <i class="fas fa-arrow-right ms-1" style="font-size:.75rem;"></i>
                </a>
            </div>
        </div>
        <div class="row g-4">
            @foreach($blogPosts as $i => $post)
            <div class="col-md-6 col-lg-4 reveal delay-{{ $i + 1 }}">
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
</section>
@endif

{{-- ── NEWSLETTER ───────────────────────────────────────────── --}}
<div class="newsletter-section"
    @if($settings->get('newsletter_bg')) style="background-image:url('{{ asset('storage/'.$settings->get('newsletter_bg')) }}');" @endif>
    <span class="newsletter-leaf left">❧</span>
    <span class="newsletter-leaf right">❧</span>
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-7 reveal">
                @if(session('newsletter_success'))
                    <div style="background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.4);padding:18px 28px;color:#e8c878;font-size:.95rem;margin-bottom:28px;">
                        <i class="fas fa-check-circle me-2"></i>{{ session('newsletter_success') }}
                    </div>
                @endif
                <span class="newsletter-eyebrow">Stay inspired</span>
                <h2>Join Our Newsletter</h2>
                <p>Get the latest projects, craft insights and exclusive offers delivered to your inbox. No spam, ever.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form mx-auto">
                    @csrf
                    <input type="email" name="email" placeholder="Enter your email address" class="newsletter-input" required>
                    <button type="submit" class="newsletter-btn">Subscribe</button>
                </form>
                <p style="font-size:.75rem;color:rgba(255,255,255,.3);margin-top:16px;margin-bottom:0;">
                    <i class="fas fa-lock me-1"></i>We respect your privacy. Unsubscribe at any time.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- ── CTA STRIP ────────────────────────────────────────────── --}}
<div class="cta-strip">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7 reveal-left">
                <h2>Ready to transform<br>your space?</h2>
                <p>Let's talk about what you're envisioning. No commitment, just a conversation.</p>
            </div>
            <div class="col-lg-5 d-flex flex-wrap gap-3 justify-content-lg-end reveal-right">
                <a href="{{ route('contact') }}" class="btn-white">
                    Get in touch <i class="fas fa-arrow-right ms-1" style="font-size:.75rem;"></i>
                </a>
                <a href="{{ route('services') }}" class="btn-outline-white">Our services</a>
            </div>
        </div>
    </div>
</div>

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
    var btnText = document.getElementById('heroBtnText');
    var btnEl   = document.getElementById('heroBtnPrimary');

    if (slides.length < 2) return;
    var current = 0, timer;

    function updateText(slide) {
        if (!content) return;
        content.classList.add('fading');
        setTimeout(function () {
            if (eyebrow) eyebrow.textContent = slide.dataset.subtitle || eyebrowDefault;
            if (title   && slide.dataset.title)    title.textContent   = slide.dataset.title;
            if (btnText && slide.dataset.btnText)  btnText.textContent = slide.dataset.btnText;
            if (btnEl   && slide.dataset.btnLink)  btnEl.href          = slide.dataset.btnLink;
            content.classList.remove('fading');
        }, 350);
    }

    function goTo(n) {
        slides[current].classList.remove('active');
        dots[current] && dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current] && dots[current].classList.add('active');
        updateText(slides[current]);
    }
    function start() { timer = setInterval(function () { goTo(current + 1); }, 6000); }
    function reset()  { clearInterval(timer); start(); }

    dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () { goTo(i); reset(); });
    });
    start();
})();
</script>
@endsection
