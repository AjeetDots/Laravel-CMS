@extends('layouts.frontend')
@section('title', isset($category) ? $category->name . ' — Blog' : 'Blog')
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        @if(isset($category))
            <span class="eyebrow">Category</span>
            <h1 class="page-hero-title-wide">{{ $category->name }}</h1>
            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif
        @else
            <span class="eyebrow">Our Journal</span>
            <h1 class="page-hero-title-wide">Blog &amp; Insights</h1>
            <p>Tips, inspiration and project stories from our studio.</p>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                @if(isset($category))
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                @endif
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">

        @if(isset($category))
        <div class="d-flex align-items-center gap-3 mb-5">
            <span style="font-size:.85rem;color:var(--ink-light);">
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
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card d-block">
                    <div class="blog-card-img-wrap">
                        @if($post->image)
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="blog-card-img">
                        @else
                            <div class="blog-card-img-placeholder"><i class="fas fa-feather-alt"></i></div>
                        @endif
                        @if($post->category)
                            <a href="{{ route('blog.category', $post->category->slug) }}"
                               class="blog-badge"
                               onclick="event.stopPropagation()">{{ $post->category->name }}</a>
                        @endif
                    </div>
                    <div class="blog-card-body">
                        <h3 class="blog-card-title">{{ $post->title }}</h3>
                        @if($post->excerpt)
                            <p class="blog-card-excerpt">{{ Str::limit($post->excerpt, 100) }}</p>
                        @endif
                        <div class="blog-card-meta">
                            @if($post->author)<span><i class="fas fa-user me-1"></i>{{ $post->author }}</span>@endif
                            <span><i class="far fa-clock me-1"></i>{{ $post->reading_time }}</span>
                        </div>
                        <span class="blog-read-more">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
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
<link href="{{ asset('css/blog.css') }}" rel="stylesheet">
@endsection
