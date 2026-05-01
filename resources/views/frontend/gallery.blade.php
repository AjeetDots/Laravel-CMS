@extends('layouts.frontend')
@section('title', 'Gallery')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">Portfolio</span>
        <h1>Our Work</h1>
        <p>A curated selection of finishes and interiors we’re proud to have delivered.</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Gallery</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        {{-- Category filters --}}
        @if($categories->count())
        <div class="gallery-filter-bar" id="filterBtns">
            <button type="button" class="gallery-filter-btn active" data-cat="all">All</button>
            @foreach($categories as $cat)
            <button type="button" class="gallery-filter-btn" data-cat="{{ $cat }}">{{ $cat }}</button>
            @endforeach
        </div>
        @endif

        <div class="row g-3" id="galleryGrid">
            @forelse($gallery as $item)
            <div class="col-6 col-md-4 col-lg-3 gal-col" data-cat="{{ $item->category ?? 'all' }}">
                <div class="gal-item">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title ?? 'Gallery' }}"
                         data-fallback="https://placehold.co/400x400/e5e0d8/6b6b65?text=Image"
                         class="img-fallback">
                    <div class="gal-overlay">
                        @if($item->title)<span>{{ $item->title }}</span>@endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5" style="color:var(--ink-light);">
                <i class="fas fa-images fa-3x mb-3" style="color:var(--border);"></i>
                <p>No gallery items yet.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
@section('scripts')
<script>
document.querySelectorAll('img.img-fallback').forEach(function(img) {
    img.addEventListener('error', function() {
        var fb = this.getAttribute('data-fallback');
        if (fb) { this.src = fb; }
    });
});

document.querySelectorAll('.gallery-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const cat = this.dataset.cat;
        document.querySelectorAll('.gallery-filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.gal-col').forEach(col => {
            col.style.display = (cat === 'all' || col.dataset.cat === cat) ? '' : 'none';
        });
    });
});
</script>
@endsection
