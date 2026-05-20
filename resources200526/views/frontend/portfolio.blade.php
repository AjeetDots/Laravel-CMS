@extends('layouts.frontend')
@section('title', 'Portfolio')
@section('body_class', 'nav-solid page-portfolio')
@section('styles')
<link href="{{ asset('css/portfolio.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">{{ $portfolioPage['intro_eyebrow'] }}</span>
        <h1 class="page-hero-title-wide">{{ $portfolioPage['intro_title'] }}</h1>
        <p>
            {{ $portfolioPage['intro_body'] }}
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $portfolioPage['breadcrumb_current'] }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        @if($tags->count())
        <div class="gallery-filter-bar mb-4" id="portfolioFilterBtns">
            <button type="button" class="gallery-filter-btn active" data-tag="all">{{ $portfolioPage['filter_all_label'] }}</button>
            @foreach($tags as $tag)
            <button type="button" class="gallery-filter-btn" data-tag="{{ $tag }}">{{ $tag }}</button>
            @endforeach
        </div>
        @endif

        <div class="row g-4" id="portfolioGrid">
            @forelse($portfolios as $project)
            @php
                $tagStr = is_array($project->tags) ? implode(',', $project->tags) : '';
            @endphp
            <div class="col-md-6 col-lg-4 portfolio-col"
                 data-tags="{{ $tagStr }}">
                <a href="{{ route('portfolio.show', $project->slug) }}" class="service-grid-card h-100">
                    <div class="service-grid-card__media">
                        <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" loading="lazy" decoding="async">
                    </div>
                    <div class="service-grid-card__body">
                        <span class="service-grid-eyebrow">
                            {{ $project->project_type === 'real' ? $portfolioPage['label_real_project'] : $portfolioPage['label_reference'] }}
                        </span>
                        <h3>{{ $project->title }}</h3>
                        @if($project->description)
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 100) }}</p>
                        @endif
                        <span class="service-grid-card__link">
                            {{ $portfolioPage['card_link_text'] }} <i class="fas fa-arrow-right" style="font-size:.65rem;" aria-hidden="true"></i>
                        </span>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5" style="color:var(--ink-light);">
                <i class="fas fa-briefcase fa-3x mb-3" style="color:var(--border);" aria-hidden="true"></i>
                <p class="mb-3">{{ $portfolioPage['empty_message'] }}</p>
                @if(!empty(trim($portfolioPage['empty_btn_text'] ?? '')) && !empty(trim($portfolioPage['empty_btn_href'] ?? '')))
                    <a href="{{ $portfolioPage['empty_btn_href'] }}" class="btn-outline-site">{{ $portfolioPage['empty_btn_text'] }}</a>
                @endif
            </div>
            @endforelse
        </div>
    </div>
</section>

<div class="cta-strip">
    <div class="container text-center">
        <h2>{{ $portfolioPage['bottom_heading'] }}</h2>
        @if(!empty(trim($portfolioPage['bottom_body'] ?? '')))
            <p class="mb-5">{{ $portfolioPage['bottom_body'] }}</p>
        @endif
        @if(!empty(trim($portfolioPage['bottom_btn_text'] ?? '')) && !empty(trim($portfolioPage['bottom_btn_href'] ?? '')))
        <a href="{{ $portfolioPage['bottom_btn_href'] }}" class="btn-white">{{ $portfolioPage['bottom_btn_text'] }} <i class="fas fa-arrow-right" style="font-size:.75rem;" aria-hidden="true"></i></a>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('#portfolioFilterBtns .gallery-filter-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var tag = this.dataset.tag;
        document.querySelectorAll('#portfolioFilterBtns .gallery-filter-btn').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');

        document.querySelectorAll('.portfolio-col').forEach(function(col) {
            var tags = (col.dataset.tags || '').split(',').map(function(t) { return t.trim(); }).filter(Boolean);
            var show = tag === 'all' || tags.indexOf(tag) !== -1;
            col.style.display = show ? '' : 'none';
        });
    });
});
</script>
@endsection
