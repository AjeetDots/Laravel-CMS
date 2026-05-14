@php
    $atelier = $atelierSection ?? [];

    $atelierEnabled = !empty($atelier['is_enabled']);
    $atelierKicker = $atelier['kicker'] ?? null;
    $atelierHeadingLine1 = $atelier['heading_line_1'] ?? null;
    $atelierHeadingLine2 = $atelier['heading_line_2'] ?? null;
    $atelierHeadingLine3 = $atelier['heading_line_3'] ?? null;
    $atelierBody = $atelier['body'] ?? null;
    $atelierCtaText = $atelier['cta_text'] ?? null;
    $atelierCtaUrl = $atelier['cta_url'] ?? null;
    $atelierBookingLabel = $atelier['booking_label'] ?? null;
    $atelierBookingUrlRaw = trim((string) ($atelier['booking_url'] ?? ''));
    $atelierBookingUrl = $atelierBookingUrlRaw !== '' ? $atelierBookingUrlRaw : null;

    $atelierPrimaryImagePath = $atelier['primary_image'] ?? null;
    $atelierSecondaryImagePath = $atelier['secondary_image'] ?? null;
    $atelierPrimaryImage = $atelierPrimaryImagePath ? asset('storage/' . ltrim($atelierPrimaryImagePath, '/')) : null;
    $atelierSecondaryImage = $atelierSecondaryImagePath ? asset('storage/' . ltrim($atelierSecondaryImagePath, '/')) : null;

    $looksLikePhoneValue = function ($value) {
        return is_string($value) && preg_match('/^\+?[\d\-\s\(\)]+$/', trim($value));
    };
    $normalizePhoneForDisplay = function ($value) {
        $value = trim((string) $value);
        if ($value === '') return null;
        if (str_starts_with(strtolower($value), 'tel:')) {
            $value = substr($value, 4);
        }
        return trim($value);
    };

    if ($atelierBookingUrl && !preg_match('/^(https?:\/\/|mailto:|tel:|\/|#)/i', $atelierBookingUrl)) {
        if ($looksLikePhoneValue($atelierBookingUrl)) {
            $atelierBookingUrl = 'tel:' . preg_replace('/[^\d+]/', '', $atelierBookingUrl);
        }
    }

    $atelierBookingDisplay = $atelierBookingUrlRaw;
    if ($atelierBookingUrl && str_starts_with(strtolower((string) $atelierBookingUrl), 'tel:')) {
        $fromHref = $normalizePhoneForDisplay($atelierBookingUrl);
        if ($fromHref && !$looksLikePhoneValue($atelierBookingUrlRaw)) {
            $atelierBookingDisplay = $fromHref;
        }
    }

    $atelierHasContent = !empty(array_filter([
        $atelierKicker,
        $atelierHeadingLine1,
        $atelierHeadingLine2,
        $atelierHeadingLine3,
        $atelierBody,
        $atelierCtaText,
        $atelierCtaUrl,
        $atelierBookingLabel,
        $atelierBookingUrl,
        $atelierPrimaryImagePath,
        $atelierSecondaryImagePath,
    ], fn ($value) => !is_null($value) && $value !== ''));
@endphp

@if($atelierEnabled && $atelierHasContent)
<section class="home-atelier section-white">
    <div class="container">
        <div class="row g-5 g-xl-5 align-items-center">
            <div class="col-lg-6 order-lg-1 reveal-left">
                <div class="home-atelier-collage" aria-hidden="true">
                    <div class="home-atelier-collage__accent"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--halo"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--dot"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--square"></div>
                    @if($atelierPrimaryImage)
                    <div class="home-atelier-collage__main">
                        <img src="{{ $atelierPrimaryImage }}"
                             alt=""
                             width="476" height="596"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--primary">
                    </div>
                    @endif
                    @if($atelierSecondaryImage)
                    <div class="home-atelier-collage__float">
                        <img src="{{ $atelierSecondaryImage }}"
                             alt=""
                             width="291" height="254"
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--inset">
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 reveal-right">
                @if($atelierKicker)
                <span class="home-atelier-kicker">{{ $atelierKicker }}</span>
                @endif
                <div class="home-atelier-headline-wrap">
                    <div class="home-atelier-headline-deco" aria-hidden="true"></div>
                    <h2 class="home-atelier-headline">
                        @if($atelierHeadingLine1)
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">{{ $atelierHeadingLine1 }}</span></span>
                        @endif
                        @if($atelierHeadingLine2)
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">{{ $atelierHeadingLine2 }}</span></span>
                        @endif
                        @if($atelierHeadingLine3)
                        <span class="home-atelier-headline-line"><span class="home-atelier-headline-inner">{{ $atelierHeadingLine3 }}</span></span>
                        @endif
                    </h2>
                </div>
                @if($atelierBody)
                <p class="home-atelier-body">
                    {{ $atelierBody }}
                </p>
                @endif
                <div class="home-atelier-actions">
                    @if(trim((string) ($atelierCtaText ?? '')) !== '')
                    <a href="{{ \App\Support\CmsOutboundHref::resolve($atelierCtaUrl !== null && trim((string) $atelierCtaUrl) !== '' ? (string) $atelierCtaUrl : null, 'contact') }}" class="hero-btn hero-btn--gold home-atelier-btn">
                        {{ $atelierCtaText }}
                        <span class="home-atelier-btn__arrow" aria-hidden="true">
                            <svg viewBox="0 0 14 14" focusable="false" aria-hidden="true">
                                <path d="M3 11L11 3"></path>
                                <path d="M5 3H11V9"></path>
                            </svg>
                        </span>
                    </a>
                    @endif
                    @if($atelierBookingUrl && $atelierBookingDisplay !== '')
                    <div class="home-atelier-phone">
                        <span class="home-atelier-phone__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M9.15 8.75c.55-1.05 1.86-.78 2.33-.2l.56.78c.23.32.22.73-.03 1.03l-.46.58c-.2.25-.24.6-.09.9.43.88 1.08 1.61 1.89 2.08.31.18.69.15.97-.07l.59-.47c.3-.24.72-.25 1.03-.02l.77.56c.73.53.8 1.69.12 2.31-1.02.93-2.56 1.1-4.34.35-1.93-.81-3.64-2.43-4.63-4.27-.9-1.66-1.05-3.08-.68-4.19z"></path>
                                <path d="M15.8 7.3c.72.15 1.28.72 1.5 1.42"></path>
                                <path d="M15.45 5.85c1.28.22 2.3 1.2 2.6 2.46"></path>
                                <path d="M7.85 15.9l-1.05.33"></path>
                            </svg>
                        </span>
                        <div class="home-atelier-phone__txt">
                            @if($atelierBookingLabel)
                            <span class="home-atelier-phone__label">{{ $atelierBookingLabel }}</span>
                            @endif
                            <a href="{{ $atelierBookingUrl }}" class="home-atelier-phone__num">{{ $atelierBookingDisplay }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif
