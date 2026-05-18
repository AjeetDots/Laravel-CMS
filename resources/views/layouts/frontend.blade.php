<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.meta-tags', ['model' => $seoModel ?? null])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    @php
        $favicon = $settings->get('site_favicon');
        $faviconUrl = $favicon ? asset('storage/'.$favicon) : asset('images/brand/favicon-bop.svg');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" sizes="any">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400;1,600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">
    <link href="{{ asset('css/inner-pages.css') }}" rel="stylesheet">
    @php $__itlCss = public_path('css/intl-phone-input.css'); $__itlCssV = is_file($__itlCss) ? filemtime($__itlCss) : time(); @endphp
    <link href="{{ asset('css/intl-phone-input.css') }}?v={{ $__itlCssV }}" rel="stylesheet">

    @yield('styles')
</head>
<body class="@yield('body_class')">

{{-- ── NAVBAR ────────────────────────────────────────────────── --}}
<nav class="site-nav" id="siteNav">
    <div class="nav-main-row">
        <div class="container">

            {{-- Logo / Brand — primary focus; eager load for LCP --}}
            <a href="{{ route('home') }}"
               class="nav-brand"
               aria-label="{{ trim((string) $settings->get('site_name')) !== '' ? trim((string) $settings->get('site_name')) . ' — Home' : 'Home' }}">
                @if($settings->get('site_logo'))
                    <img src="{{ asset('storage/' . $settings->get('site_logo')) }}"
                         alt=""
                         class="nav-logo-img"
                         fetchpriority="high"
                         decoding="async"
                         loading="eager">
                @else
                    @include('partials.brand-logo-nav')
                @endif
            </a>

            {{-- Links + actions: desktop = inline; mobile = full-screen sheet when open --}}
            <div class="nav-mobile-sheet" id="navMobileSheet">
                <ul class="nav-links" id="navLinks">
                    @foreach($navMenus as $menu)
                        @if($menu->children->count())
                            <li class="has-drop">
                                <a href="{{ $menu->url ?? '#' }}"
                                   class="{{ $menu->isActiveForNav() ? 'active' : '' }}">
                                    {{ $menu->label }}
                                    <i class="fas fa-chevron-down ms-1" style="font-size:.5rem;opacity:.6;"></i>
                                </a>
                                <div class="drop-menu">
                                    @foreach($menu->children as $child)
                                        <a href="{{ $child->url }}" target="{{ $child->target }}">{{ $child->label }}</a>
                                    @endforeach
                                </div>
                            </li>
                        @else
                            <li>
                                <a href="{{ $menu->url ?? '#' }}" target="{{ $menu->target ?? '_self' }}"
                                   class="{{ $menu->isActiveForNav() ? 'active' : '' }}">
                                    {{ $menu->label }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>

                <div class="nav-right" id="navRight">
                    <div class="nav-top-contacts d-none d-xl-flex">
                        @if(\App\Support\SitePhone::hasPhone($settings))
                            <a href="tel:{{ \App\Support\SitePhone::telHref($settings) }}">
                                <i class="fas fa-phone"></i> {{ \App\Support\SitePhone::display($settings) }}
                            </a>
                        @endif
                    </div>
                    <a href="{{ route('contact') }}" class="btn-quote">Get in touch <i class="fa-solid fa-arrow-up-right btn-quote__ico" aria-hidden="true"></i></a>
                </div>
            </div>

            <button type="button"
                    class="nav-toggle"
                    id="navToggle"
                    aria-expanded="false"
                    aria-controls="navMobileSheet"
                    aria-label="Open menu">
                <span class="nav-toggle-icon nav-toggle-icon--bars" aria-hidden="true"><i class="fas fa-bars"></i></span>
                <span class="nav-toggle-icon nav-toggle-icon--close" aria-hidden="true"><i class="fas fa-xmark"></i></span>
            </button>

        </div>
    </div>
</nav>
<div class="nav-mobile-backdrop" id="navMobileBackdrop" hidden aria-hidden="true"></div>

{{-- ── SITE TOASTS (top-right, auto-dismiss) ────────────────── --}}
@include('partials.site-toasts')

{{-- ── PAGE CONTENT ──────────────────────────────────────────── --}}
@yield('content')

{{-- ── Chat Floating button ───────────────────────────────── --}}
@php
    $whatsappChatUrl = \App\Support\SitePhone::whatsappHref(
        $settings,
        "Hello Bespoke Ornate, I'd like to discuss a project."
    );
@endphp

@if($whatsappChatUrl !== '')
<a href="{{ $whatsappChatUrl }}"
   class="whatsapp-float home-atelier-btn"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Chat with us on WhatsApp">
    <i class="fas fa-comments"></i>
    <span>Chat With Us</span>
</a>
@endif

{{-- ── FOOTER ────────────────────────────────────────────────── --}}
<footer class="site-footer">
    <div class="container footer-main">
        <div class="row g-5 g-xl-5 align-items-start">
            {{-- Brand --}}
            <div class="col-12 col-lg-4">
                <div class="footer-brand">
                @php $footerLogo = $settings->get('site_logo_footer') ?: $settings->get('site_logo'); @endphp
                @if($footerLogo)
                    <a href="{{ route('home') }}" class="footer-logo-link" aria-label="{{ trim((string) $settings->get('site_name')) !== '' ? trim((string) $settings->get('site_name')) . ' — Home' : 'Home' }}">
                        <img src="{{ asset('storage/' . $footerLogo) }}" alt="" class="footer-logo-img" loading="lazy" decoding="async">
                    </a>
                @else
                    <a href="{{ route('home') }}" class="footer-logo-link" aria-label="{{ trim((string) $settings->get('site_name')) !== '' ? trim((string) $settings->get('site_name')) . ' — Home' : 'Home' }}">
                        @include('partials.brand-logo-footer')
                    </a>
                @endif
                </div>
                @if(trim((string) $settings->get('footer_about')) !== '')
                <p class="about-text">{{ $settings->get('footer_about') }}</p>
                @endif
                <div class="footer-social mt-4">
                    @if($settings->get('social_facebook'))
                        <a href="{{ $settings->get('social_facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($settings->get('social_twitter'))
                        <a href="{{ $settings->get('social_twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if($settings->get('social_linkedin'))
                        <a href="{{ $settings->get('social_linkedin') }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                    @if($settings->get('social_instagram'))
                        <a href="{{ $settings->get('social_instagram') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
            </div>
            {{-- Explore (IA aligned with proposal: Services, Finishes, Gallery, Portfolio) --}}
            <div class="col-12 col-sm-6 col-lg-2">
                <h6 class="footer-col-title">Explore</h6>
                <nav class="footer-nav" aria-label="Explore">
                    <a href="{{ route('finishes') }}">Finishes</a>
                    <a href="{{ route('services') }}">Services</a>
                    <a href="{{ route('gallery') }}">Gallery</a>
                    <!-- <a href="{{ route('portfolio') }}">Portfolio</a> -->
                    <a href="{{ route('blog.index') }}">Blog</a>
                </nav>
            </div>
            {{-- Company --}}
            <div class="col-12 col-sm-6 col-lg-2">
                <h6 class="footer-col-title">Company</h6>
                <nav class="footer-nav" aria-label="Company">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ url('/about') }}">About Us</a>
                    <a href="{{ route('contact') }}">Contact</a>
                    <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>
                    <a href="{{ url('/terms-and-conditions') }}">Terms &amp; Conditions</a>
                    <a href="{{ url('/cookie-policy') }}">Cookie Policy</a>
                </nav>
            </div>
            {{-- Contact + newsletter: one column (stacked) so the footer reads as four areas, not five --}}
            <div class="col-12 col-lg-4">
                <div class="footer-connect">
                    <h6 class="footer-col-title">Get in touch</h6>
                   
                    @if(\App\Support\SitePhone::hasPhone($settings))
                    <div class="footer-contact-line">
                        
                        <a href="tel:{{ \App\Support\SitePhone::telHref($settings) }}"><i class="fa-solid fa-phone"></i> {{ \App\Support\SitePhone::display($settings) }}</a>
                    </div>
                    @endif
                    @if(\App\Support\SitePhone::hasWhatsapp($settings))
                    <div class="footer-contact-line">
                        <a href="{{ \App\Support\SitePhone::whatsappHref($settings) }}" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-whatsapp"></i> {{ \App\Support\SitePhone::whatsappDisplay($settings) }}</a>
                    </div>
                    @endif
                    <!-- @if($settings->get('site_address'))
                    <div class="footer-contact-line">
                        <i class="fa-regular fa-location-dot" aria-hidden="true"></i>
                        <span>{{ $settings->get('site_address') }}</span>
                    </div>
                    @endif -->
                     @if($settings->get('site_email'))
                    <div class="footer-contact-line">
                        <a href="mailto:{{ $settings->get('site_email') }}"><i class="fa-solid fa-envelope"></i> {{ $settings->get('site_email') }}</a>
                    </div>
                    @endif
                     {{-- <div class="footer-contact-line">
                        <a href="#"><i class="fa-brands fa-square-instagram"></i> @bespoken</a>
                    </div> --}}
                    <div class="footer-newsletter-wrap">
                        @include('partials.newsletter-form-compact')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-flex justify-content-center align-items-center flex-wrap gap-2">
            <span>
                @if($settings->get('copyright_text'))
                    {!! $settings->get('copyright_text') !!}
                @elseif(trim((string) $settings->get('site_name')) !== '')
                    &copy; {{ date('Y') }} <span class="gold"> {{ $settings->get('site_name') }} </span> All rights reserved.
                @endif
            </span>
            <!-- <span>Website Designed & Developed by: <a href="https://www.dotsquares.com/" target="_blank"><img src="{{ asset('images/dotsquaresit.png') }}" alt="Dotsquares" class="footer-logo" /></a> Dotsquares</span> -->
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mobile nav: sheet + backdrop + bars/X + scroll lock (iOS-friendly)
(function () {
    var toggle   = document.getElementById('navToggle');
    var sheet    = document.getElementById('navMobileSheet');
    var nav      = document.getElementById('siteNav');
    var backdrop = document.getElementById('navMobileBackdrop');
    if (!toggle || !sheet || !nav) return;

    function setMenuOpen(open) {
        sheet.classList.toggle('is-open', open);
        nav.classList.toggle('menu-open', open);
        toggle.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        document.body.classList.toggle('nav-menu-open', open);
        if (backdrop) {
            backdrop.setAttribute('aria-hidden', open ? 'false' : 'true');
            if (open) {
                backdrop.removeAttribute('hidden');
            } else {
                backdrop.setAttribute('hidden', '');
            }
        }
    }

    toggle.addEventListener('click', function () {
        setMenuOpen(!sheet.classList.contains('is-open'));
    });
    if (backdrop) {
        backdrop.addEventListener('click', function () {
            setMenuOpen(false);
        });
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sheet.classList.contains('is-open')) {
            setMenuOpen(false);
        }
    });
    sheet.addEventListener('click', function (e) {
        var a = e.target.closest('a');
        if (!a) return;
        var h = a.getAttribute('href');
        if (h && h !== '#' && h.indexOf('javascript:') !== 0) {
            setMenuOpen(false);
        }
    });
})();

// Navbar: transparent over hero, solid after scroll / on inner pages
(function() {
    const nav   = document.getElementById('siteNav');
    const hero  = document.getElementById('hero');

    // Non-hero pages get solid nav immediately
    if (!hero) { nav.classList.add('always-solid'); return; }

    function onScroll() {
        const navH = nav ? nav.getBoundingClientRect().height : 120;
        const heroBottom = hero.getBoundingClientRect().bottom;
        nav.classList.toggle('scrolled', heroBottom <= navH + 20);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();

// 3-Panel hero slider
(function() {
    const slides  = document.querySelectorAll('.hero3-slide');
    const progs   = document.querySelectorAll('.hero3-prog');
    const counter = document.querySelector('.hero3-current');
    if (!slides.length) return;
    let current = 0, timer;

    function goTo(n) {
        slides[current].classList.remove('active');
        progs[current]?.classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        progs[current]?.classList.add('active');
        if (counter) counter.textContent = String(current + 1).padStart(2, '0');
    }
    function start() { timer = setInterval(() => goTo(current + 1), 5500); }
    function reset() { clearInterval(timer); start(); }

    document.querySelector('.hero3-prev')?.addEventListener('click', () => { goTo(current - 1); reset(); });
    document.querySelector('.hero3-next')?.addEventListener('click', () => { goTo(current + 1); reset(); });
    start();
})();

// Scroll reveal
(function() {
    const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    if (!els.length) return;
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    els.forEach(el => io.observe(el));
})();

// Animated counters for stats
(function() {
    const nums = document.querySelectorAll('[data-count]');
    if (!nums.length) return;
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const target = +el.dataset.count;
            const suffix = el.dataset.suffix || '';
            const dur = 1800;
            const step = 16;
            const inc = target / (dur / step);
            let cur = 0;
            const t = setInterval(() => {
                cur = Math.min(cur + inc, target);
                el.textContent = Math.floor(cur) + suffix;
                if (cur >= target) clearInterval(t);
            }, step);
            io.unobserve(el);
        });
    }, { threshold: 0.5 });
    nums.forEach(el => io.observe(el));
})();
</script>
@php $__itlJs = public_path('js/intl-phone-input.js'); $__itlJsV = is_file($__itlJs) ? filemtime($__itlJs) : time(); @endphp
<script src="{{ asset('js/intl-phone-input.js') }}?v={{ $__itlJsV }}" defer></script>
@php $__cfaJs = public_path('js/contact-form-ajax.js'); $__cfaJsV = is_file($__cfaJs) ? filemtime($__cfaJs) : time(); @endphp
<script src="{{ asset('js/contact-form-ajax.js') }}?v={{ $__cfaJsV }}" defer></script>
@yield('scripts')
</body>
</html>
