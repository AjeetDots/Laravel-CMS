@php
    $commissionsCfg = $commissionsSection ?? [];
    $commissionsEnabled = array_key_exists('is_enabled', $commissionsCfg) ? !empty($commissionsCfg['is_enabled']) : true;
    $commissionsEyebrow = $commissionsCfg['eyebrow'] ?? '';
    $commissionsHeadingLine1 = $commissionsCfg['heading_line_1'] ?? '';
    $commissionsBtnText = $commissionsCfg['button_text'] ?? '';
    $commissionsBtnUrl = $commissionsCfg['button_url'] ?? '';
@endphp

@if($gallery->count() && $commissionsEnabled)
@php $commissionItems = $gallery->take(8); @endphp
<section class="commissions-section">
    <div class="container">

        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
            <div class="reveal-left">
                <span class="eyebrow">{{ $commissionsEyebrow }}</span>
                <span class="section-rule" style="margin-bottom:0;"></span>
                <h2 class="home-section-title-lg" style="margin:10px 0 0;">
                    {{ $commissionsHeadingLine1 }}
                </h2>
            </div>
            <div class="reveal-right d-flex flex-wrap gap-2 justify-content-lg-end">
                @if(trim($commissionsBtnUrl) !== '' && trim($commissionsBtnText) !== '')
                <a href="{{ $commissionsBtnUrl }}" class="btn-outline-site" style="font-size:.65rem;padding:10px 20px;">
                    {{ $commissionsBtnText }} <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
                @endif
            </div>
        </div>

        <div class="commissions-grid reveal">
            @foreach($commissionItems as $item)
            <a href="{{ route('gallery') }}" class="commission-item">
                @if($item->image)
                    <img src="{{ $item->image_url }}"
                         alt="{{ $item->title ?? '' }}"
                         class="commission-img">
                @else
                    <div class="commission-placeholder"><i class="fas fa-paint-brush"></i></div>
                @endif
                <div class="commission-overlay"></div>
                <div class="commission-body">
                    <div class="commission-meta">
                        @if($item->category)
                            <span class="commission-category">{{ is_object($item->category) ? $item->category->name : $item->category }}</span>
                        @endif
                        <p class="commission-title">{{ $item->title ?? '' }}</p>
                    </div>
                    <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif
