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
    $beginCtaBgUrl = !empty($beginCtaCfg['bg_image'])
        ? asset('storage/' . $beginCtaCfg['bg_image'])
        : '';
@endphp

@if($beginCtaEnabled)
<section class="home-begin-cta position-relative">
    @if($beginCtaBgUrl !== '')
    <div class="home-begin-cta__bg" style="background-image:url('{{ $beginCtaBgUrl }}');"></div>
    @else
    <div class="home-begin-cta__bg home-begin-cta__bg--empty" aria-hidden="true"></div>
    @endif
    <div class="home-begin-cta__overlay"></div>
    <div class="container position-relative text-center home-begin-cta__inner">
        <span class="home-begin-cta__eyebrow">{{ $beginCtaEyebrow }}</span>
        <h2 class="home-begin-cta__title">{{ $beginCtaTitleLine1 }} <br />{{ $beginCtaTitleLine2 }}</h2>
        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
            @if(trim((string) $beginCtaPrimaryText) !== '')
            <a href="{{ \App\Support\CmsOutboundHref::resolve($beginCtaPrimaryUrl !== null && trim((string) $beginCtaPrimaryUrl) !== '' ? (string) $beginCtaPrimaryUrl : null, 'contact') }}" class="hero-btn hero-btn--gold">{{ $beginCtaPrimaryText }}</a>
            @endif
            @if(trim((string) $beginCtaSecondaryText) !== '')
            <a href="{{ \App\Support\CmsOutboundHref::resolve($beginCtaSecondaryUrl !== null && trim((string) $beginCtaSecondaryUrl) !== '' ? (string) $beginCtaSecondaryUrl : null, 'contact') }}" class="hero-btn-outline hero-btn-outline--hero home-begin-cta__ghost">
                <i class="fas fa-phone"></i>
                {{ $beginCtaSecondaryText }}
            </a>
            @endif
        </div>
    </div>
</section>
@endif
