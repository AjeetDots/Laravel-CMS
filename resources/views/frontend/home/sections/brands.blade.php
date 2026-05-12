@php
    $brandsStripCfg = $brandsStripSection ?? [];
    $brandsStripEnabled = array_key_exists('is_enabled', $brandsStripCfg) ? !empty($brandsStripCfg['is_enabled']) : true;
    $brandsStripKicker = $brandsStripCfg['kicker'] ?? '';
    $brandsStripTitleLine1 = $brandsStripCfg['title_line_1'] ?? '';
    $brandsStripTitleLine2 = $brandsStripCfg['title_line_2'] ?? '';
    $brandMarqueeSegments = isset($brandsStripCfg['marquee_segments']) ? (int) $brandsStripCfg['marquee_segments'] : 8;
    if ($brandMarqueeSegments < 1 || $brandMarqueeSegments > 20) {
        $brandMarqueeSegments = 8;
    }
@endphp

@if($brands->count() && $brandsStripEnabled)
<section class="brands-strip" aria-labelledby="brands-strip-title">
    <div class="container">
        <header class="brands-strip__head">
            <span class="brands-strip__rule" aria-hidden="true"></span>
            <div class="brands-strip__title-block">
                <span class="brands-strip__kicker">{{ $brandsStripKicker }}</span>
                <h2 class="brands-strip__title" id="brands-strip-title">{{ $brandsStripTitleLine1 }} <br />{{ $brandsStripTitleLine2 }}</h2>
            </div>
            <span class="brands-strip__rule" aria-hidden="true"></span>
        </header>
    </div>
    <div class="brands-strip__marquee" role="presentation">
        <div class="brands-slider-wrap">
            <div class="brand-track" style="--brand-segments: {{ $brandMarqueeSegments }}">
                @foreach(range(1, $brandMarqueeSegments) as $seg)
                <div class="brand-track__segment" @if($seg > 1) aria-hidden="true" @endif>
                    @foreach($brands as $brand)
                    <div class="brand-logo-item">
                        <div class="brand-logo-item__frame">
                            @if($brand->logo)
                                <img class="brand-logo-img" src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" loading="lazy" decoding="async">
                            @else
                                <span class="brand-placeholder">{{ $brand->name }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
