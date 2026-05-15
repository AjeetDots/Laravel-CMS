@php
    $heroEyebrows = [
        'faq' => 'Help center',
        'docs' => 'Documentation',
        'help' => 'Support',
    ];
    $eyebrowCustom = trim((string) ($customHeroEyebrow ?? ''));
    $heroEyebrow = $eyebrowCustom !== ''
        ? $eyebrowCustom
        : ($heroEyebrows[$page->slug] ?? '');
    $titleCustom = trim((string) ($customHeroTitle ?? ''));
    $ledeCustom = trim((string) ($customHeroLede ?? ''));
@endphp

<div class="page-hero">
    <div class="container">
        @if($heroEyebrow !== '')
            <span class="eyebrow">{{ $heroEyebrow }}</span>
        @endif
        <h1 class="page-hero-title-wide">
            @if($titleCustom !== '')
                {!! nl2br(e($titleCustom)) !!}
            @else
                {{ $page->title }}
            @endif
        </h1>
        @if($ledeCustom !== '')
            <p>{!! nl2br(e($ledeCustom)) !!}</p>
        @elseif($page->meta_description && \Illuminate\Support\Str::length($page->meta_description) < 220)
            <p>{{ $page->meta_description }}</p>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
            </ol>
        </nav>
    </div>
</div>
