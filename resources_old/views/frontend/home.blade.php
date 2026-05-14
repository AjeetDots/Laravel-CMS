@extends('layouts.frontend')
@section('title', trim((string) ($homePageBrowserTitle ?? '')) !== '' ? $homePageBrowserTitle : 'Home')
@section('body_class', 'has-hero page-home')
@section('styles')
    @php
        $__homeCss = public_path('css/home.css');
        $__homeCssV = is_file($__homeCss) ? filemtime($__homeCss) : time();
    @endphp
    <link href="{{ asset('css/home.css') }}?v={{ $__homeCssV }}" rel="stylesheet">
@endsection

@section('content')
    @include('frontend.home.sections.hero')
    @include('frontend.home.sections.atelier')
    @include('frontend.home.sections.finishes')
    @include('frontend.home.sections.services')
    @include('frontend.home.sections.commissions')
    @include('frontend.home.sections.why')
    @include('frontend.home.sections.process')
    @include('frontend.home.sections.testimonials')
    @include('frontend.home.sections.begin-cta')
    @include('frontend.home.sections.contact-band')
    @include('frontend.home.sections.brands')
    @include('frontend.home.sections.blog-preview')

@endsection

@section('scripts')
    <script>
        // Multi-slide hero
        (function() {
            var slides = document.querySelectorAll('.hero-slide-item');
            var dots = document.querySelectorAll('.hero-dot');
            var content = document.querySelector('.hero-full-content');
            var heroEl = document.getElementById('hero');
            var contactHref = heroEl && heroEl.dataset.heroFallbackContactHref ? heroEl.dataset.heroFallbackContactHref : '';
            var eyebrowDefault = heroEl && heroEl.dataset.heroEyebrowDefault ? heroEl.dataset.heroEyebrowDefault : '';
            var eyebrow = document.getElementById('heroEyebrow');
            var title = document.getElementById('heroTitle');
            var leadEl = document.getElementById('heroLead');
            var btnText = document.getElementById('heroBtnText');
            var btnEl = document.getElementById('heroBtnPrimary');
            var btn2Text = document.getElementById('heroBtnSecondaryText');
            var btn2El = document.getElementById('heroBtnSecondary');
            var leadDefault = heroEl && heroEl.dataset.heroLeadDefault ? heroEl.dataset.heroLeadDefault : '';

            function resolveBtnHref(raw) {
                var h = (raw || '').trim();
                return h.length ? h : contactHref;
            }

            if (slides.length < 2) return;
            var current = 0,
                timer;

            function renderHeroTitleFromSlide(titleEl, slide) {
                if (!titleEl || !slide) return;
                var main = slide.getAttribute('data-title') || '';
                titleEl.textContent = main;
                titleEl.classList.remove('hero-full-title--lines');
            }

            function updateText(slide) {
                if (!content) return;
                content.classList.add('fading');
                setTimeout(function() {
                    var sub = slide.getAttribute('data-subtitle');
                    if (eyebrow) eyebrow.textContent = (sub && sub.length) ? sub : eyebrowDefault;
                    if (title) renderHeroTitleFromSlide(title, slide);
                    var bt = slide.getAttribute('data-btn-text');
                    if (btnEl && btnText) {
                        var t = (bt || '').trim();
                        if (!t.length) {
                            btnEl.classList.add('d-none');
                            btnEl.setAttribute('aria-hidden', 'true');
                        } else {
                            btnEl.classList.remove('d-none');
                            btnEl.removeAttribute('aria-hidden');
                            btnText.textContent = t;
                            var bh = slide.getAttribute('data-btn-link');
                            btnEl.setAttribute('href', resolveBtnHref(bh));
                        }
                    }
                    var b2t = slide.getAttribute('data-btn2-text');
                    if (btn2El && btn2Text) {
                        var t2 = (b2t || '').trim();
                        if (!t2.length) {
                            btn2El.classList.add('d-none');
                            btn2El.setAttribute('aria-hidden', 'true');
                        } else {
                            btn2El.classList.remove('d-none');
                            btn2El.removeAttribute('aria-hidden');
                            btn2Text.textContent = t2;
                            var b2h = slide.getAttribute('data-btn2-link');
                            btn2El.setAttribute('href', resolveBtnHref(b2h));
                        }
                    }
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

            function start() {
                timer = setInterval(function() {
                    goTo(current + 1);
                }, 6000);
            }

            function reset() {
                clearInterval(timer);
                start();
            }

            dots.forEach(function(dot, i) {
                dot.addEventListener('click', function() {
                    goTo(i);
                    reset();
                });
            });
            start();
        })();

        // Testimonial full-width slider
        (function() {
            var track = document.getElementById('testiTrack');
            var btnPrev = document.getElementById('testiPrev');
            var btnNext = document.getElementById('testiNext');
            var dotsWrap = document.getElementById('testiDots');
            var dotEls = dotsWrap ? dotsWrap.querySelectorAll('.testi-dot--pill') : [];
            if (!track) return;
            var slides = track.querySelectorAll('.testi-slide');
            if (slides.length < 2) return;

            var current = 0,
                timer;

            function goTo(n) {
                current = ((n % slides.length) + slides.length) % slides.length;
                track.style.transform = 'translateX(-' + (current * 100) + '%)';
                dotEls.forEach(function(d, i) {
                    d.classList.toggle('active', i === current);
                });
            }

            function start() {
                timer = setInterval(function() {
                    goTo(current + 1);
                }, 5000);
            }

            function reset() {
                clearInterval(timer);
                start();
            }

            btnPrev && btnPrev.addEventListener('click', function() {
                goTo(current - 1);
                reset();
            });
            btnNext && btnNext.addEventListener('click', function() {
                goTo(current + 1);
                reset();
            });
            dotEls.forEach(function(d, i) {
                d.addEventListener('click', function() {
                    goTo(i);
                    reset();
                });
            });
            start();
        })();

        // Blog strip — scroll dots (mobile)
        (function() {
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
            var measureStep = function() {
                var first = row.children[0];
                if (!first) return 320;
                var gap = parseFloat(window.getComputedStyle(row).gap) || 24;
                return first.getBoundingClientRect().width + gap;
            };
            sc.addEventListener(
                'scroll',
                function() {
                    var step = measureStep();
                    var idx = Math.min(dots.length - 1, Math.max(0, Math.round(sc.scrollLeft / step)));
                    dots.forEach(function(d, j) {
                        d.classList.toggle('active', j === idx);
                    });
                }, {
                    passive: true
                }
            );
        })();
    </script>
@endsection
