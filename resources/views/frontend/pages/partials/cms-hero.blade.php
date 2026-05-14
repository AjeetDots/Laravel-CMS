@php
    $heroEyebrows = [
        'faq' => 'Help center',
        'docs' => 'Documentation',
        'help' => 'Support',
    ];
    $heroEyebrow = $heroEyebrows[$page->slug] ?? '';
@endphp

<div class="page-hero">
    <div class="container">
        @if(trim((string) $heroEyebrow) !== '')
            <span class="eyebrow">{{ $heroEyebrow }}</span>
        @endif
        <h1 class="page-hero-title-wide">{{ $page->title }}</h1>
        @if($page->meta_description && \Illuminate\Support\Str::length($page->meta_description) < 220)
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
