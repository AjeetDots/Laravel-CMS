@if($brands->count())
<section class="brands-strip" aria-labelledby="brands-strip-title">
    <div class="container">
        <header class="brands-strip__head">
            <span class="brands-strip__rule" aria-hidden="true"></span>
            <div class="brands-strip__title-block">
                <span class="brands-strip__kicker">Partners &amp; collaborators</span>
                <h2 class="brands-strip__title" id="brands-strip-title">Trusted by <br />leading names</h2>
            </div>
            <span class="brands-strip__rule" aria-hidden="true"></span>
        </header>
    </div>
    @php $brandMarqueeSegments = 8; @endphp
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
