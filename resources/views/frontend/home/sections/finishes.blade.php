@php
    $finishesCfg = $finishesSection ?? [];
    $finishesEnabled = array_key_exists('is_enabled', $finishesCfg) ? !empty($finishesCfg['is_enabled']) : true;
    $finishesEyebrow = $finishesCfg['eyebrow'] ?? 'The Finishes';
    $finishesHeadingLine1 = $finishesCfg['heading_line_1'] ?? 'Six surfaces,';
    $finishesHeadingLine2 = $finishesCfg['heading_line_2'] ?? 'infinite tones.';
    $finishesCardLabel = $finishesCfg['card_label'] ?? 'Finish';
    $finishesBtnText = $finishesCfg['button_text'] ?? 'All finishes';
    $finishesBtnUrl = $finishesCfg['button_url'] ?? route('finishes');
@endphp

@if($finishesEnabled)
<section class="commissions-section finishes-home-band">
    <div class="container">
        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
            <div class="reveal-left">
                <span class="eyebrow">{{ $finishesEyebrow }}</span>
                <span class="section-rule" style="margin-bottom:0;"></span>
                <h2 class="home-section-title-lg">
                    {{ $finishesHeadingLine1 }}<br />{{ $finishesHeadingLine2 }}
                </h2>
            </div>
            <div class="reveal-right">
                <a href="{{ $finishesBtnUrl }}" class="btn-outline-site" style="font-size:.65rem;padding:10px 20px;">
                    {{ $finishesBtnText }} <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>

        @if($finishes->count())
        <div class="commissions-grid home-finishes-grid reveal">
            @foreach($finishes->take(6) as $i => $f)
            <a href="{{ route('finishes.show', $f->slug) }}" class="commission-item @if($i === 0) is-lead @endif">
                @if($f->cover_image)
                    <img src="{{ $f->cover_image_url }}" alt="{{ $f->title }}" class="commission-img">
                @else
                    <div class="commission-placeholder"><i class="fas fa-paint-brush"></i></div>
                @endif
                <div class="commission-overlay"></div>
                <div class="commission-body">
                    <div class="commission-meta">
                        <span class="commission-category">{{ $finishesCardLabel }}</span>
                        <p class="commission-title">{{ $f->title }}</p>
                    </div>
                    <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="commissions-grid home-finishes-grid reveal">
            @foreach(['Marmorino', 'Tadelakt', 'Metallic', 'Concrete', 'Spatulato', 'Travertino'] as $i => $name)
            <a href="{{ route('finishes') }}" class="commission-item @if($i === 0) is-lead @endif">
                <div class="commission-placeholder"><i class="fas fa-palette"></i></div>
                <div class="commission-overlay"></div>
                <div class="commission-body">
                    <div class="commission-meta">
                        {{-- <span class="commission-category">Sample</span> --}}
                        <p class="commission-title">{{ $name }}</p>
                    </div>
                    <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif
