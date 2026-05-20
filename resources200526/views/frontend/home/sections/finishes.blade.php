@php
    $finishesCfg = $finishesSection ?? [];
    $finishesEnabled = array_key_exists('is_enabled', $finishesCfg) ? !empty($finishesCfg['is_enabled']) : true;
    $finishesEyebrow = $finishesCfg['eyebrow'] ?? '';
    $finishesHeadingLine1 = $finishesCfg['heading_line_1'] ?? '';
    $finishesHeadingLine2 = $finishesCfg['heading_line_2'] ?? '';
    $finishesCardLabel = $finishesCfg['card_label'] ?? '';
    $finishesBtnText = $finishesCfg['button_text'] ?? '';
    $finishesBtnUrl = $finishesCfg['button_url'] ?? '';
@endphp

@if($finishesEnabled)
<section class="commissions-section finishes-home-band">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
            <div class="reveal-left">
                <span class="finishes-intro__eyebrow">{{ $finishesEyebrow }}</span>
                <h2 class="home-atelier-headline-white">
                    {{ $finishesHeadingLine1 }}<br />{{ $finishesHeadingLine2 }}
                </h2>
            </div>
            <div class="reveal-right">
                @if(trim($finishesBtnUrl) !== '' && trim($finishesBtnText) !== '')
                <a href="{{ $finishesBtnUrl }}" class="btn-outline-site" style="font-size:.65rem;padding:10px 20px;">
                    {{ $finishesBtnText }} <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
                @endif
            </div>
        </div>

        @if($finishes->count())
        <div class="commissions-grid home-finishes-grid reveal">
            @foreach($finishes->take(6) as $i => $f)
            <a href="{{ route('finishes.show', $f->slug) }}" class="commission-item @if($i === 0) is-lead @endif">
                <div class="imgFinshes">
                    <img src="{{ $f->thumbnail_url }}" alt="{{ $f->title }}" class="commission-img" loading="lazy" decoding="async">
                </div>
                <div class="commission-body">
                    <div class="commission-meta">
                        <span class="home-why-card__title">{{ $finishesCardLabel }}</span>
                        <p class="commission-title">{{ $f->title }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
