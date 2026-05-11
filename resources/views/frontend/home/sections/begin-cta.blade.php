@php
    $beginCtaCfg = $beginCtaSection ?? [];
    $beginCtaEnabled = array_key_exists('is_enabled', $beginCtaCfg) ? !empty($beginCtaCfg['is_enabled']) : true;
    $beginCtaEyebrow = $beginCtaCfg['eyebrow'] ?? 'Begin a Project';
    $beginCtaTitleLine1 = $beginCtaCfg['title_line_1'] ?? 'Transform your space';
    $beginCtaTitleLine2 = $beginCtaCfg['title_line_2'] ?? 'into a quiet masterpiece.';
    $beginCtaPrimaryText = $beginCtaCfg['primary_btn_text'] ?? 'Get free consultation';
    $beginCtaPrimaryUrl = $beginCtaCfg['primary_btn_url'] ?? route('contact');
    $beginCtaSecondaryText = $beginCtaCfg['secondary_btn_text'] ?? 'Call the studio';
    $beginCtaSecondaryUrl = $beginCtaCfg['secondary_btn_url'] ?? null;
    $sitePhone = isset($settings) && method_exists($settings, 'get') ? \App\Support\SitePhone::display($settings) : null;
    $siteTelHref = isset($settings) && method_exists($settings, 'get') ? \App\Support\SitePhone::telHref($settings) : '';
    if (empty($beginCtaSecondaryUrl) && $siteTelHref !== '') {
        $beginCtaSecondaryUrl = 'tel:'.$siteTelHref;
    }
    $beginCtaBgUrl = !empty($beginCtaCfg['bg_image'])
        ? asset('storage/' . $beginCtaCfg['bg_image'])
        : 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1920&q=80';
@endphp

@if($beginCtaEnabled)
<section class="home-begin-cta position-relative">
    <div class="home-begin-cta__bg" style="background-image:url('{{ $beginCtaBgUrl }}');"></div>
    <div class="home-begin-cta__overlay"></div>
    <div class="container position-relative text-center home-begin-cta__inner">
        <span class="home-begin-cta__eyebrow">{{ $beginCtaEyebrow }}</span>
        <h2 class="home-begin-cta__title">{{ $beginCtaTitleLine1 }} <br />{{ $beginCtaTitleLine2 }}</h2>
        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
            @if(!empty($beginCtaPrimaryText))
            <a href="{{ $beginCtaPrimaryUrl }}" class="hero-btn hero-btn--gold">{{ $beginCtaPrimaryText }}</a>
            @endif
            @if(!empty($beginCtaSecondaryText) && !empty($beginCtaSecondaryUrl))
            <a href="{{ $beginCtaSecondaryUrl }}" class="hero-btn-outline hero-btn-outline--hero home-begin-cta__ghost">
                <i class="fas fa-phone"></i>
                {{ $beginCtaSecondaryText }}
            </a>
            @endif
        </div>
    </div>
</section>
@endif
