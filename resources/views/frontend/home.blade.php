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
    $heroEyebrowDefault = 'Hand-applied plaster, architectural finishes, and bespoke interiors for distinguished homes and commercial spaces.';
@endphp

{{-- ── HERO ─────────────────────────────────────────────────── --}}
<section class="hero-full hero-full--premium" id="hero" data-hero-eyebrow-default="{{ e($heroEyebrowDefault) }}">

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
                <div class="hero-full-content hero-full-content--stagger">
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

{{-- ── INTRO ────────────────────────────────────────────────── --}}
<section class="intro-section intro-section--atelier section-white">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5 reveal-left">
                <span class="eyebrow">Our Philosophy</span>
                <span class="section-rule"></span>
                <h2 class="intro-headline">
                    <span class="intro-headline-line">Surfaces that hold the light,</span>
                    <span class="intro-headline-line">walls that hold the room.</span>
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
<section class="section section-soft disciplines-section">
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
@if($gallery->count())
@php $commissionItems = $gallery->take(6); @endphp
<section class="commissions-section">
    <div class="container">

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

{{-- ── TESTIMONIALS ─────────────────────────────────────────── --}}
@if($testimonials->count())
<section class="testi-section">
    <div class="container">
        <div class="text-center testi-header reveal">
            <span class="eyebrow">Client voices</span>
            <span class="section-rule centered"></span>
            <h2 class="testi-header-title">Trusted by discerning<br>clients worldwide.</h2>
        </div>
    </div>
    <div class="testi-slider-wrap">
        <div class="testi-track" id="testiTrack">
            @foreach($testimonials as $i => $t)
            <div class="testi-slide">
                <div class="testi-slide-content">
                    <span class="testi-quote-mark">"</span>
                    <div class="testi-stars">
                        @for($s = 1; $s <= ($t->rating ?? 5); $s++)
                            <i class="fas fa-star"></i>
                        @endfor
                    </div>
                    <p class="testi-text">{{ $t->message }}</p>
                    <div class="testi-author-wrap">
                        <div class="testi-avatar-lg">
                            @if($t->client_image)
                                <img src="{{ $t->client_image_url }}" alt="{{ $t->client_name }}">
                            @else
                                {{ strtoupper(substr($t->client_name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="testi-author-name">{{ $t->client_name }}</div>
                        <div class="testi-author-role">
                            {{ $t->client_position }}{{ $t->client_company ? ', ' . $t->client_company : '' }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($testimonials->count() > 1)
        <button class="testi-btn testi-btn-prev" id="testiPrev" aria-label="Previous testimonial">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="testi-btn testi-btn-next" id="testiNext" aria-label="Next testimonial">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="testi-dots" id="testiDots">
            @foreach($testimonials as $i => $t)
            <button class="testi-dot {{ $i === 0 ? 'active' : '' }}" data-idx="{{ $i }}" aria-label="Slide {{ $i+1 }}"></button>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif


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
                <span class="eyebrow">Our Journal</span>
                <span class="section-rule"></span>
                <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.9rem,4vw,2.8rem);font-weight:700;letter-spacing:-.3px;margin-bottom:0;">
                    Insights &amp; stories<br>from the studio.
                </h2>
            </div>
            <div class="col-12 col-lg-3 offset-lg-2 text-lg-end mt-3 mt-lg-0 reveal-right">
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

{{-- ── CTA STRIP (actions only — newsletter lives in footer once) ── --}}
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
    var dotEls  = document.querySelectorAll('.testi-dot');
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
</script>
@endsection
