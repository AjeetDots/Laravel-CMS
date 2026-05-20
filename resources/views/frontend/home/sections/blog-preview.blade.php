@php
    $blogPreviewCfg = $blogPreviewSection ?? [];
    $blogPreviewEnabled = array_key_exists('is_enabled', $blogPreviewCfg) ? !empty($blogPreviewCfg['is_enabled']) : true;
    $blogPreviewEyebrow = $blogPreviewCfg['eyebrow'] ?? '';
    $blogPreviewHeading = $blogPreviewCfg['heading'] ?? '';
    $blogPreviewButtonText = trim((string) ($blogPreviewCfg['button_text'] ?? ''));
    if ($blogPreviewButtonText === '') {
        $blogPreviewButtonText = 'All Blogs';
    }
@endphp

@if($blogPosts->count() && $blogPreviewEnabled)
<section class="section section-white home-journal">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-lg-7 reveal-left">
                <span class="finishes-intro__eyebrow">{{ $blogPreviewEyebrow }}</span>
                <h2 class="home-atelier-headline">
                    {{ $blogPreviewHeading }}
                </h2>
            </div>
            <div class="col-lg-4 offset-lg-1 text-lg-end mt-3 mt-lg-0 reveal-right secondary-button">
                <a href="{{ route('blog.index') }}" class="btn-outline-quote-dark hover-gold">
                    {{ $blogPreviewButtonText }} <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="home-blog-scroller" id="homeBlogScroller">
            <div class="row g-4  pb-1 home-blog-row">
            @foreach($blogPosts as $i => $post)
            <div class="col-12 col-md-6 col-lg-4 flex-shrink-0 flex-lg-shrink-1 reveal delay-{{ $i + 1 }}">
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card d-block h-100">
                    <div class="blog-card-img-wrap">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="blog-card-img" loading="lazy" decoding="async">
                    </div>
                    <div class="blog-card-body">
                        @if($post->category_name !== '')
                            <span class="finishes-intro__eyebrow">{{ $post->category_name }}</span>
                        @endif
                        <h3 class="home-why-card__title-dark">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <!-- <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 90) }}</p> -->
                        @endif
                        <!-- <div class="blog-card-meta">
                            @if($post->author)<span><i class="fas fa-user me-1"></i>{{ $post->author }}</span>@endif
                            <span><i class="far fa-clock me-1"></i>{{ $post->reading_time }}</span>
                        </div> -->
                    </div>
                </a>
            </div>
            @endforeach
            </div>
        </div>
        <!-- @if($blogPosts->count() > 1)
        <div class="home-blog-dots d-flex justify-content-center gap-2 mt-3" id="homeBlogDots" aria-hidden="true"></div>
        @endif -->
    </div>
</section>
@endif
