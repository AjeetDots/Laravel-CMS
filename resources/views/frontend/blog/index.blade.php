@extends('layouts.frontend')
@section('title', isset($category) ? $category->name . ' — Blog' : 'Blog')
@section('body_class', 'nav-solid page-blog')
@section('content')

<section class="finishes-intro">
    <div class="container">
        
            <span class="finishes-intro__eyebrow">Our Journal</span>
            <h1 class="finishes-intro__title">Blog &amp; Insights</h1>
            <p class="finishes-intro__desc">Tips, inspiration and project stories from our studio.</p>
     
      
    </div>
</section>

<section class="section section-white ">
    <div class="container">

        @if(isset($category))
        <div class="d-flex align-items-center gap-3 mb-5">
            <span style="font-size:.85rem;color:var(--secondary);">
                <i class="fas fa-folder-open me-1"></i>
                Showing posts in <strong>{{ $category->name }}</strong>
                ({{ $posts->total() }} {{ Str::plural('post', $posts->total()) }})
            </span>
            <a href="{{ route('blog.index') }}" class="btn-outline-site btn-sm-site ms-auto">
                <i class="fas fa-times me-1"></i>Clear Filter
            </a>
        </div>
        @endif

        <div class="row g-4">
            @forelse($posts as $post)
            <div class="col-md-6 col-lg-4 reveal delay-{{ ($loop->index % 3) + 1 }}">
                <article class="blog-card h-100">
                    <div class="blog-card-img-wrap">
                        <a href="{{ route('blog.show', $post->slug) }}" class="d-block" tabindex="-1" aria-hidden="true">
                            <img src="{{ $post->image_url }}" alt="" class="blog-card-img" loading="lazy" decoding="async">
                        </a>
                        @if($post->postCategory)
                            <a href="{{ route('blog.category', $post->postCategory->slug) }}" class="blog-badge">{{ $post->postCategory->name }}</a>
                        @endif
                    </div>
                    <div class="blog-card-body">
                        <h3 class="blog-card-title">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        @if($post->excerpt)
                            <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 100) }}</p>
                        @endif
                        <div class="blog-card-meta">
                            @if($post->author)<span><i class="fas fa-user me-1"></i>{{ $post->author }}</span>@endif
                            <span><i class="far fa-clock me-1"></i>{{ $post->reading_time }}</span>
                        </div>
                        <a href="{{ route('blog.show', $post->slug) }}" class="blog-read-more">Read More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-feather-alt fa-3x mb-3" style="color:var(--border);"></i>
                @if(isset($category))
                    <p class="text-muted">No posts in this category yet.</p>
                    <a href="{{ route('blog.index') }}" class="btn-outline-site mt-2">View all posts</a>
                @else
                    <p class="text-muted">No posts published yet.</p>
                @endif
            </div>
            @endforelse
        </div>

        @if($posts->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>

@endsection

@section('styles')
@php $__blogCss = public_path('css/blog.css'); $__blogCssV = is_file($__blogCss) ? filemtime($__blogCss) : time(); @endphp
<link href="{{ asset('css/blog.css') }}?v={{ $__blogCssV }}" rel="stylesheet">
@endsection
