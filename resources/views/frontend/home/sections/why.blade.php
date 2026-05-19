@php
    $whyCfg = $whySection ?? [];
    $whyEnabled = array_key_exists('is_enabled', $whyCfg) ? !empty($whyCfg['is_enabled']) : true;
    $whyEyebrow = $whyCfg['eyebrow'] ?? '';
    $whyHeading = $whyCfg['heading'] ?? '';
    $whyCards = is_array($whyCfg['cards'] ?? null) ? $whyCfg['cards'] : [];
    $whyIconImageMap = \App\Support\HomeWhyCardIcons::imageMap();
@endphp

@if($whyEnabled)
<section class="home-why section-soft">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-end mb-5">
            <div class="col-lg-5 reveal-left">
                <span class="finishes-intro__eyebrow">{{ $whyEyebrow }}</span>
                <h2 class="home-atelier-headline-white">{{ $whyHeading }}</h2>
            </div>
        </div>
        <div class="row g-4">
            @foreach($whyCards as $index => $card)
            @php
                $icon = trim((string) ($card['icon'] ?? ''));
                $icon = str_replace('fa-solid', '', $icon);
                $icon = trim($icon);
                $iconSrc = $whyIconImageMap[$icon] ?? null;
                if ($iconSrc === null && $icon !== '' && (str_contains($icon, '/') || preg_match('/\.(png|svg|jpe?g|webp)$/i', $icon))) {
                    $iconSrc = str_starts_with($icon, 'http://') || str_starts_with($icon, 'https://') || str_starts_with($icon, '//')
                        ? $icon
                        : asset(ltrim($icon, '/'));
                }
                $iconFaClass = $iconSrc === null ? $icon : '';
            @endphp
            @continue(empty(trim((string) ($card['title'] ?? ''))) && empty(trim((string) ($card['desc'] ?? ''))) && $icon === '')
            <div class="col-md-6 col-xl-3 reveal delay-{{ $index + 1 }}">
                <div class="home-why-card">
                    @if($iconSrc)
                    <span class="home-why-card__icon" aria-hidden="true"><img src="{{ $iconSrc }}" alt="" /></span>
                    @elseif($iconFaClass !== '')
                    <span class="home-why-card__icon" aria-hidden="true"><i class="fa-solid {{ $iconFaClass }}"></i></span>
                    @endif
                    <h3 class="home-why-card__title">{{ $card['title'] ?? '' }}</h3>
                    <p class="home-why-card__desc">{{ $card['desc'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
