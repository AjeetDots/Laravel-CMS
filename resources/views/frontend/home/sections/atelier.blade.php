@php
    $sitePhone = \App\Support\SitePhone::display($settings);
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
    $atelierBookingText = $atelier['booking_text'] ?? null;
    $atelierBookingUrlRaw = trim((string) ($atelier['booking_url'] ?? ''));
    $atelierBookingUrl = $atelierBookingUrlRaw !== '' ? $atelierBookingUrlRaw : null;

    $atelierPrimaryImagePath = $atelier['primary_image'] ?? null;
    $atelierSecondaryImagePath = $atelier['secondary_image'] ?? null;
    $atelierPrimaryImage = \App\Support\CmsImage::resolve($atelierPrimaryImagePath);
    $atelierSecondaryImage = \App\Support\CmsImage::resolve($atelierSecondaryImagePath);

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

    $bookingUrlDisplayPhone = $atelierBookingUrlRaw;
    if (!$bookingUrlDisplayPhone && $atelierBookingUrl && str_starts_with(strtolower($atelierBookingUrl), 'tel:')) {
        $bookingUrlDisplayPhone = $atelierBookingUrl;
    }
    $bookingUrlDisplayPhone = $normalizePhoneForDisplay($bookingUrlDisplayPhone);

    if (!$atelierBookingText && $atelierBookingUrlRaw && $looksLikePhoneValue($atelierBookingUrlRaw)) {
        $atelierBookingText = $atelierBookingUrlRaw;
    }

    if (!$atelierBookingUrl && $atelierBookingText) {
        $atelierBookingUrl = 'tel:' . preg_replace('/[^\d+]/', '', $atelierBookingText);
    }

    // If URL contains a phone number, prefer showing that number over generic text like "Booking Now".
    // Do not replace the studio default line (dial + number from settings).
    if (
        $bookingUrlDisplayPhone
        && trim((string) $atelierBookingText) !== trim((string) $sitePhone)
        && (
            ! $atelierBookingText
            || ! $looksLikePhoneValue($atelierBookingText)
        )
    ) {
        $atelierBookingText = $bookingUrlDisplayPhone;
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
        $atelierBookingText,
        $atelierBookingUrl,
        $atelierPrimaryImagePath,
        $atelierSecondaryImagePath,
    ], fn ($value) => !is_null($value) && $value !== ''));
@endphp

@if($atelierEnabled && $atelierHasContent)
<section class="home-atelier section-white">
    <div class="lines_image"></div>
    <div class="container">
        <div class="row g-5 g-xl-5 align-items-center">
            <div class="col-lg-6 order-lg-1 reveal-left">
                <div class="home-atelier-collage" aria-hidden="true">
                    <div class="home-atelier-collage__accent"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--halo"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--dot"></div>
                    <div class="home-atelier-collage__shape home-atelier-collage__shape--square"></div>
                    <div class="home-atelier-collage__main">
                        <img src="{{ $atelierPrimaryImage }}"
                             alt=""
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--primary img-fluid">
                    </div>
                    <div class="blue_Section">
                        <img src="{{ asset('images/blue.png') }}" class="img-fluid" alt=""/>
                    </div>
                    <div class="home-atelier-collage__float">
                        <img src="{{ $atelierSecondaryImage }}"
                             alt=""
                             loading="lazy" decoding="async"
                             class="home-atelier-collage__img home-atelier-collage__img--inset img-fluid">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 reveal-right">
                @if($atelierKicker)
                <span class="finishes-intro__eyebrow">{{ $atelierKicker }}</span>
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
                    @if($atelierCtaText && $atelierCtaUrl)
                    <a href="{{ $atelierCtaUrl }}" class="hero-btn hero-btn--gold home-atelier-btn">
                        {{ $atelierCtaText }}
                        <span class="home-atelier-btn__arrow" aria-hidden="true">
                            <i class="fa-solid fa-arrow-up-right btn-quote__ico"></i>
                        </span>
                    </a>
                    @endif
                    @if($atelierBookingText && $atelierBookingUrl)
                    <div class="home-atelier-phone">
                        <span class="home-atelier-phone__icon" aria-hidden="true">
                            <img src="../images/booking-icon.svg" class="img-fluid"/>
                        </span>
                        <div class="home-atelier-phone__txt">
                           
                            <div>
                                <div>
                             @if($atelierBookingLabel)
                            <p class=" book-now">{{ $atelierBookingLabel }}</p>
                            @endif
                            </div>
                                <div><a href="{{ $atelierBookingUrl }}" class="home-atelier-phone__num">{{ $atelierBookingText }}</a></div>
                            </div>
                            
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif
