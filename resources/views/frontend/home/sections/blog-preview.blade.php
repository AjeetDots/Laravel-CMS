@php
    $blogPreviewCfg = $blogPreviewSection ?? [];
    $blogPreviewEnabled = array_key_exists('is_enabled', $blogPreviewCfg) ? !empty($blogPreviewCfg['is_enabled']) : true;
    $blogPreviewEyebrow = $blogPreviewCfg['eyebrow'] ?? '';
    $blogPreviewHeading = $blogPreviewCfg['heading'] ?? '';
    $blogPreviewButtonText = $blogPreviewCfg['button_text'] ?? '';
    $blogPreviewButtonUrl = $blogPreviewCfg['button_url'] ?? '';
    $blogPreviewReadMoreText = $blogPreviewCfg['read_more_text'] ?? '';
@endphp

@if($blogPosts->count() && $blogPreviewEnabled)
<section class="section section-white home-journal">
    <div class="container">
        <div class="row align-items-end mb-5">
            <div class="col-12 col-lg-7 reveal-left">
                <span class="eyebrow">{{ $blogPreviewEyebrow }}</span>
                <span class="section-rule"></span>
                <h2 class="home-blog-heading mb-0">
                    {{ $blogPreviewHeading }}
                </h2>
            </div>
            <div class="col-12 col-lg-3 offset-lg-2 text-lg-end mt-3 mt-lg-0 reveal-right">
                @if(trim($blogPreviewButtonUrl) !== '' && trim($blogPreviewButtonText) !== '')
                <a href="{{ $blogPreviewButtonUrl }}" class="btn-outline-site">
                    {{ $blogPreviewButtonText }} <i class="fas fa-arrow-right ms-1" style="font-size:.75rem;"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="home-blog-scroller" id="homeBlogScroller">
            <div class="row g-4 flex-nowrap flex-lg-wrap pb-1 home-blog-row">
            @foreach($blogPosts as $i => $post)
            <div class="col-10 col-md-6 col-lg-4 flex-shrink-0 flex-lg-shrink-1 reveal delay-{{ $i + 1 }}">
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card d-block h-100">
                    <div class="blog-card-img-wrap">
                        @if($post->image)
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="blog-card-img">
                        @else
                            <div class="blog-card-img-placeholder"><i class="fas fa-feather-alt"></i></div>
                        @endif
                        @if($post->category)<span class="blog-badge">{{ $post->category }}</span>@endif
                    </div>
                    <div class="blog-card-body">
                        <h3 class="blog-card-title">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 90) }}</p>
                        @endif
                        <div class="blog-card-meta">
                            @if($post->author)<span><i class="fas fa-user me-1"></i>{{ $post->author }}</span>@endif
                            <span><i class="far fa-clock me-1"></i>{{ $post->reading_time }}</span>
                        </div>
                        <span class="blog-read-more">{{ $blogPreviewReadMoreText }} <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            @endforeach
            </div>
        </div>
        @if($blogPosts->count() > 1)
        <div class="home-blog-dots d-flex d-lg-none justify-content-center gap-2 mt-3" id="homeBlogDots" aria-hidden="true"></div>
        @endif
    </div>
</section>
@endif
