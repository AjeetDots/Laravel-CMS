@if($gallery->count())
@php $commissionItems = $gallery->take(8); @endphp
<section class="commissions-section">
    <div class="container">

        <div class="d-flex align-items-end justify-content-between mb-4 flex-wrap gap-3">
            <div class="reveal-left">
                <span class="eyebrow">Selected Work</span>
                <span class="section-rule" style="margin-bottom:0;"></span>
                <h2 class="home-section-title-lg" style="margin:10px 0 0;">
                    Recent commissions.
                </h2>
            </div>
            <div class="reveal-right d-flex flex-wrap gap-2 justify-content-lg-end">
                <a href="{{ route('gallery') }}" class="btn-outline-site" style="font-size:.65rem;padding:10px 20px;">
                    View full gallery <i class="fas fa-arrow-right ms-1" style="font-size:.65rem;"></i>
                </a>
            </div>
        </div>

        <div class="commissions-grid reveal">
            @foreach($commissionItems as $item)
            <a href="{{ route('gallery') }}" class="commission-item">
                @if($item->image)
                    <img src="{{ $item->image_url }}"
                         alt="{{ $item->title ?? 'Commission' }}"
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
                        <p class="commission-title">{{ $item->title ?? 'Untitled' }}</p>
                    </div>
                    <div class="commission-arrow"><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</section>
@endif
