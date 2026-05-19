@php
    $beginCtaCfg = $beginCtaSection ?? [];
    $beginCtaEnabled = array_key_exists('is_enabled', $beginCtaCfg) ? !empty($beginCtaCfg['is_enabled']) : true;
    $beginCtaEyebrow = $beginCtaCfg['eyebrow'] ?? '';
    $beginCtaTitleLine1 = $beginCtaCfg['title_line_1'] ?? '';
    $beginCtaTitleLine2 = $beginCtaCfg['title_line_2'] ?? '';
    $beginCtaPrimaryText = $beginCtaCfg['primary_btn_text'] ?? '';
    $beginCtaPrimaryUrl = $beginCtaCfg['primary_btn_url'] ?? '';
    $beginCtaSecondaryText = $beginCtaCfg['secondary_btn_text'] ?? '';
    $beginCtaSecondaryUrl = $beginCtaCfg['secondary_btn_url'] ?? '';
    $beginCtaBgUrl = \App\Support\CmsImage::resolve($beginCtaCfg['bg_image'] ?? null);
@endphp

@if($beginCtaEnabled)
<section class="home-begin-cta position-relative home-begin-cta--has-bg">
    <div class="home-begin-cta__bg home-begin-cta__bg--dynamic"
         style="--begin-cta-bg-image: url('{{ e($beginCtaBgUrl) }}');"
         aria-hidden="true"></div>
    <div class="home-begin-cta__overlay"></div>
    <div class="container position-relative text-center home-begin-cta__inner">
        <span class="finishes-intro__eyebrow">{{ $beginCtaEyebrow }}</span>
        <h2 class="home-atelier-headline-white">{{ $beginCtaTitleLine1 }} <br />{{ $beginCtaTitleLine2 }}</h2>

        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4 hero-full-btns">
            @if(trim((string) $beginCtaPrimaryText) !== '')
            <a href="{{ \App\Support\CmsOutboundHref::resolve($beginCtaPrimaryUrl !== null && trim((string) $beginCtaPrimaryUrl) !== '' ? (string) $beginCtaPrimaryUrl : null, 'contact') }}" class="hero-btn hero-btn--gold">
               {{ $beginCtaPrimaryText }} <i class="fa-solid fa-arrow-up-right"  aria-hidden="true"></i></a>
            @endif
            @if(trim((string) $beginCtaSecondaryText) !== '')
            <a href="{{ \App\Support\CmsOutboundHref::resolve($beginCtaSecondaryUrl !== null && trim((string) $beginCtaSecondaryUrl) !== '' ? (string) $beginCtaSecondaryUrl : null, 'contact') }}" class="hero-btn-outline hero-btn-outline--hero home-begin-cta__ghost">
                <i class="fas fa-phone" aria-hidden="true"></i>
                {{ $beginCtaSecondaryText }}
            </a>
            @endif
        </div>
    </div>
</section>
@endif
