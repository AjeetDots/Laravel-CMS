@php $sitePhone = $settings->get('site_phone'); @endphp

<section class="home-atelier section-white">
    <div class="container">
        <div class="row g-5 g-xl-5 align-items-center">
            <div class="col-lg-6 order-lg-1 reveal-left">
                <div class="home-atelier-collage" aria-hidden="true">
                    <div class="home-atelier-collage__accent"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--halo"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--dot"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--square"></div>
                    <div class="home-atelier-collage__main">
                        <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?w=1200&q=80"
                             alt=""
                             width="476" height="596"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--primary">
                    </div>
                    <div class="home-atelier-collage__float">
                        <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=900&q=80"
                             alt=""
                             width="291" height="254"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--inset">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 reveal-right">
                <span class="home-atelier-kicker">The Atelier</span>
                <div class="home-atelier-headline-wrap">
                    <div class="home-atelier-headline-deco" aria-hidden="true"></div>
                    <h2 class="home-atelier-headline">
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">Surfaces that hold</span></span>
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">the light, walls that</span></span>
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">hold the room.</span></span>
                    </h2>
                </div>
                <p class="home-atelier-body">
                    For over one decades we have collaborated with leading interior designers,
                    architects and private clients to create plaster finishes of uncommon depth and quietude.
                    Every wall is mixed, applied and polished by hand.
                </p>
                <div class="home-atelier-actions">
                    <a href="{{ route('contact') }}" class="hero-btn hero-btn--gold home-atelier-btn">
                        Get a Quote
                        <span class="home-atelier-btn__arrow" aria-hidden="true">
                            <svg viewBox="0 0 14 14" focusable="false" aria-hidden="true">
                                <path d="M3 11L11 3"></path>
                                <path d="M5 3H11V9"></path>
                            </svg>
                        </span>
                    </a>
                    @if($sitePhone)
                    <div class="home-atelier-phone">
                        <span class="home-atelier-phone__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                                <rect x="1.5" y="1.5" width="21" height="21" rx="2"></rect>
                                <path d="M9.3 8.8c.6-1.2 2-.9 2.5-.2l.6.9c.2.3.2.7 0 1l-.5.8c-.2.3-.2.7 0 1.1.5.9 1.2 1.7 2.1 2.2.3.2.7.2 1 0l.8-.5c.3-.2.7-.2 1 0l.9.6c.8.5 1 2 .1 2.6-1.1.8-2.6.9-4 .3-2-.8-3.8-2.5-4.9-4.4-1-1.7-1.1-3.3-.6-4.4z"></path>
                                <path d="M15.6 7.6c.9.2 1.6.9 1.9 1.7"></path>
                                <path d="M15.3 5.6c1.6.3 2.9 1.5 3.3 3"></path>
                            </svg>
                        </span>
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
