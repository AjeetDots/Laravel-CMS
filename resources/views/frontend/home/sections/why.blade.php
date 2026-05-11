@php
    $whyCfg = $whySection ?? [];
    $whyEnabled = array_key_exists('is_enabled', $whyCfg) ? !empty($whyCfg['is_enabled']) : true;
    $whyEyebrow = $whyCfg['eyebrow'] ?? 'Why Bespoke Ornate';
    $whyHeading = $whyCfg['heading'] ?? 'A studio defined by its hands.';
    $whyLead = $whyCfg['lead'] ?? 'Each project is led by master artisans trained in traditional Italian techniques and refined for the demands of contemporary architecture.';
    $whyCards = is_array($whyCfg['cards'] ?? null) ? $whyCfg['cards'] : [];
@endphp

@if($whyEnabled)
<section class="home-why section-soft">
    <div class="container">
        <div class="row g-4 g-lg-5 align-items-end mb-5">
            <div class="col-lg-5 reveal-left">
                <span class="eyebrow">{{ $whyEyebrow }}</span>
                <span class="section-rule"></span>
                <h2 class="home-section-title-md mb-0">{{ $whyHeading }}</h2>
            </div>
            <div class="col-lg-6 offset-lg-1 reveal-right">
                <p class="home-why-lead mb-0">
                    {{ $whyLead }}
                </p>
            </div>
        </div>
        <div class="row g-4">
            @foreach($whyCards as $index => $card)
            @php
                $icon = trim((string) ($card['icon'] ?? 'fa-award'));
                $icon = str_replace('fa-solid', '', $icon);
                $icon = trim($icon);
                if ($icon === '') {
                    $icon = 'fa-award';
                }
            @endphp
            <div class="col-md-6 col-xl-3 reveal delay-{{ $index + 1 }}">
                <div class="home-why-card">
                    <span class="home-why-card__icon" aria-hidden="true"><i class="fa-solid {{ $icon }}"></i></span>
                    <h3 class="home-why-card__title">{{ $card['title'] ?? '' }}</h3>
                    <p class="home-why-card__desc">{{ $card['desc'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
