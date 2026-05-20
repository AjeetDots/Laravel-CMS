@php
    $heroEyebrow = trim((string) ($page->hero_eyebrow ?? ''));
    $heroLede = trim((string) ($page->hero_lede ?? ''));
    if ($heroLede === '' && $page->meta_description && \Illuminate\Support\Str::length($page->meta_description) < 220) {
        $heroLede = trim((string) $page->meta_description);
    }
@endphp

<section class="finishes-intro">
    <div class="container">
        @if($heroEyebrow !== '')
            <span class="finishes-intro__eyebrow">{{ $heroEyebrow }}</span>
        @endif
        <h1 class="finishes-intro__title">{{ $page->title }}</h1>
        @if($heroLede !== '')
            <p class="finishes-intro__desc">{{ $heroLede }}</p>
        @endif
    </div>
</section>
