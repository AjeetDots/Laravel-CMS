@extends('layouts.frontend')
@section('title', 'Gallery')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">Gallery</span>
        <h1 class="page-hero-title-wide">Our Work</h1>
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
                <div class="gal-item gal-item--zoom" role="button" tabindex="0"
                     data-bs-toggle="modal" data-bs-target="#galleryLightbox"
                     data-img="{{ $item->image_url }}"
                     data-title="{{ $item->title ?? 'Gallery' }}">
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

{{-- Lightbox (proposal: zoom view) --}}
<div class="modal fade" id="galleryLightbox" tabindex="-1" aria-labelledby="galleryLightboxTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 py-2 px-3">
                <h2 class="modal-title fs-6 text-white-50 mb-0" id="galleryLightboxTitle"></h2>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="" alt="" id="galleryLightboxImg" class="w-100" style="max-height:85vh;object-fit:contain;background:#111;">
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
document.getElementById('galleryLightbox')?.addEventListener('show.bs.modal', function (event) {
    var trigger = event.relatedTarget;
    if (!trigger) return;
    var img = trigger.getAttribute('data-img');
    var title = trigger.getAttribute('data-title') || '';
    document.getElementById('galleryLightboxImg').src = img || '';
    document.getElementById('galleryLightboxImg').alt = title;
    document.getElementById('galleryLightboxTitle').textContent = title;
});

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
