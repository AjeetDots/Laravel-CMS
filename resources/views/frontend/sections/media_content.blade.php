@php
    $hasImage = !empty($data['image']);
    $posRaw = strtolower(trim((string) ($data['image_position'] ?? 'left')));
    $imageRight = $hasImage && ($posRaw === 'right');
    $layoutKey = $layout ?? 'full';
    $isBuilder = in_array($layoutKey, ['full', 'sidebar'], true);
    $isSidebar = $layoutKey === 'sidebar';
    $colClass = $hasImage
        ? ($isSidebar ? 'col-12' : 'col-12 col-lg-6')
        : 'col-12';
    $textOrderClass = $imageRight ? 'order-1 order-lg-1' : 'order-2 order-lg-2';
    $mediaOrderClass = $imageRight ? 'order-2 order-lg-2' : 'order-1 order-lg-1';
@endphp

@if($hasImage && ! $imageRight)
    <div class="{{ $colClass }} {{ $mediaOrderClass }} {{ $isBuilder ? 'cms-builder-split__media' : 'mb-4 mb-lg-0' }}">
        <div class="media-frame about-feature-frame {{ $isBuilder ? 'cms-builder-media'.($isSidebar ? ' cms-builder-media--sidebar' : '') : 'cms-section-media__frame' }}">
            <img
                src="{{ asset('storage/'.$data['image']) }}"
                alt="{{ $data['title'] }}"
                class="feature-img img-fluid w-100"
                loading="lazy"
                decoding="async"
            >
        </div>
    </div>
@endif

<div class="{{ $colClass }} {{ $textOrderClass }} {{ $isBuilder ? 'cms-builder-split__copy cms-builder-copy' : 'cms-section-media__prose pt-0 pt-lg-4' }}">
    <span class="story-label {{ $isBuilder ? 'cms-builder-label' : '' }}">{{ $data['title'] }}</span>
    <div class="{{ $isBuilder ? 'cms-builder-copy__body page-content' : '' }}">
        {!! $data['content'] !!}
    </div>

    @if(!empty($data['buttons']))
        <div class="d-flex gap-2 gap-sm-3 mt-4 flex-wrap {{ $isBuilder ? 'cms-builder-actions' : '' }}">
            @foreach($data['buttons'] as $button)
                <a href="{{ $button['link'] }}" class="btn btn-dark {{ $isBuilder ? 'cms-builder-btn' : 'btn-cms-section' }}">{{ $button['text'] }}</a>
            @endforeach
        </div>
    @endif
</div>

@if($imageRight)
    <div class="{{ $colClass }} {{ $mediaOrderClass }} {{ $isBuilder ? 'cms-builder-split__media mt-4 mt-lg-0' : 'mt-4 mt-lg-0' }}">
        <div class="media-frame about-feature-frame {{ $isBuilder ? 'cms-builder-media'.($isSidebar ? ' cms-builder-media--sidebar' : '') : 'cms-section-media__frame' }}">
            <img
                src="{{ asset('storage/'.$data['image']) }}"
                alt="{{ $data['title'] }}"
                class="feature-img img-fluid w-100"
                loading="lazy"
                decoding="async"
            >
        </div>
    </div>
@endif
