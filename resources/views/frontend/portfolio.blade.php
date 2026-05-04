@extends('layouts.frontend')
@section('title', 'Portfolio')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">Completed work</span>
        <h1 class="page-hero-title-wide">Portfolio</h1>
        <p>
            Project-based inspiration — reference imagery and real commissions. Explore by tag or open a project for the full story.
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Portfolio</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        @if($tags->count())
        <div class="gallery-filter-bar mb-4" id="portfolioFilterBtns">
            <button type="button" class="gallery-filter-btn active" data-tag="all">All</button>
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
                        @if($project->cover_image)
                            <img src="{{ $project->cover_image_url }}" alt="{{ $project->title }}" loading="lazy" decoding="async">
                        @else
                            <div class="service-grid-card__placeholder"><i class="fas fa-briefcase"></i></div>
                        @endif
                    </div>
                    <div class="service-grid-card__body">
                        <span class="service-grid-eyebrow">
                            {{ $project->project_type === 'real' ? 'Real project' : 'Reference' }}
                        </span>
                        <h3>{{ $project->title }}</h3>
                        @if($project->description)
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($project->description), 100) }}</p>
                        @endif
                        <span class="service-grid-card__link">
                            View project <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                        </span>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center py-5" style="color:var(--ink-light);">
                <i class="fas fa-briefcase fa-3x mb-3" style="color:var(--border);"></i>
                <p>No portfolio entries yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<div class="cta-strip">
    <div class="container text-center">
        <h2>Planning something similar?</h2>
        <p class="mb-5">Share your brief and we’ll outline timelines and options.</p>
        <a href="{{ route('contact') }}" class="btn-white">Start an enquiry <i class="fas fa-arrow-right" style="font-size:.75rem;"></i></a>
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
