{{-- Footer columns: Admin → Appearance → Footer navigation (two fixed slots). --}}
@php
    $fb = $footerNavBySlot ?? [];
@endphp
@foreach ([1, 2] as $slot)
    @php
        $block = $fb[$slot] ?? ['title' => '', 'links' => collect()];
        $title = $block['title'] ?? '';
        $links = $block['links'] ?? collect();
    @endphp
    <div class="col-12 col-sm-6 col-lg-2">
        <h6 class="footer-col-title">{{ $title }}</h6>
        <nav class="footer-nav" aria-label="{{ $title }}">
            @foreach ($links as $link)
                <a href="{{ $link->url }}"
                   target="{{ $link->target }}"
                   @if($link->target === '_blank') rel="noopener noreferrer" @endif
                   class="{{ $link->matchesCurrentPath() ? 'active' : '' }}">{{ $link->label }}</a>
            @endforeach
        </nav>
    </div>
@endforeach
