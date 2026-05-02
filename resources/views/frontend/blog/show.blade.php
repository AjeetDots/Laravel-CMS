@extends('layouts.frontend')
@section('title', $post->title)
@section('body_class', 'nav-solid')
@section('content')

{{-- ── Hero ─────────────────────────────────────────────────────── --}}
<div class="page-hero">
    <div class="container">
        @if($post->category)
            <a href="{{ route('blog.category', $post->category->slug) }}"
               class="eyebrow eyebrow-link">{{ $post->category->name }}</a>
        @else
            <span class="eyebrow">Journal</span>
        @endif
        <h1 class="page-hero-title-wide">{{ $post->title }}</h1>
        <div class="blog-hero-meta">
            @if($post->author)<span><i class="fas fa-user me-1"></i>{{ $post->author }}</span>@endif
            @if($post->published_at)<span><i class="far fa-calendar me-1"></i>{{ $post->published_at->format('d F Y') }}</span>@endif
            <span><i class="far fa-clock me-1"></i>{{ $post->reading_time }}</span>
        </div>
        <nav aria-label="breadcrumb" class="mt-1">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                @if($post->category)
                    <li class="breadcrumb-item">
                        <a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">{{ Str::limit($post->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ── Main + Sidebar ───────────────────────────────────────────── --}}
<section class="section section-white">
    <div class="container">
        <div class="row g-5">

            {{-- Main Content --}}
            <div class="col-lg-8">
                @if($post->image)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                         style="width:100%;border-radius:12px;margin-bottom:36px;max-height:480px;object-fit:cover;">
                @endif

                <div class="blog-post-content">
                    {!! $post->content !!}
                </div>

                {{-- Category tag at bottom --}}
                @if($post->category)
                <div class="mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <span style="font-size:.83rem;color:var(--ink-light);font-weight:500;text-transform:uppercase;letter-spacing:.08em;">Filed under</span>
                    <a href="{{ route('blog.category', $post->category->slug) }}" class="blog-cat-tag ms-2">
                        <i class="fas fa-folder me-1"></i>{{ $post->category->name }}
                    </a>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('blog.index') }}" class="btn-outline-site">
                        <i class="fas fa-arrow-left me-2"></i>Back to Blog
                    </a>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                {{-- Category Widget --}}
                @if($post->category)
                <div class="sidebar-widget mb-4">
                    <h4 class="sidebar-widget-title">Category</h4>
                    <a href="{{ route('blog.category', $post->category->slug) }}" class="sidebar-cat-link">
                        <i class="fas fa-folder-open me-2"></i>{{ $post->category->name }}
                        <span class="ms-auto"><i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                @endif

                {{-- Latest Posts Widget --}}
                @if($latestPosts->count())
                <div class="sidebar-widget">
                    <h4 class="sidebar-widget-title">Latest Posts</h4>
                    <ul class="sidebar-post-list">
                        @foreach($latestPosts as $lp)
                        <li class="sidebar-post-item">
                            <a href="{{ route('blog.show', $lp->slug) }}" class="sidebar-post-link">
                                @if($lp->image)
                                    <img src="{{ $lp->image_url }}" alt="{{ $lp->title }}" class="sidebar-post-thumb">
                                @else
                                    <div class="sidebar-post-thumb-placeholder"><i class="fas fa-feather-alt"></i></div>
                                @endif
                                <div class="sidebar-post-info">
                                    <span class="sidebar-post-title">{{ Str::limit($lp->title, 55) }}</span>
                                    @if($lp->published_at)
                                        <span class="sidebar-post-date">{{ $lp->published_at->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

{{-- ── Related Posts ────────────────────────────────────────────── --}}
@if($related->count())
<section class="section section-soft">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">More to read</span>
            <h2 style="font-size:1.8rem;">Related Posts</h2>
            <span class="section-rule centered"></span>
        </div>
        <div class="row g-4">
            @foreach($related as $r)
            <div class="col-md-4">
                <a href="{{ route('blog.show', $r->slug) }}" class="blog-card d-block">
                    <div class="blog-card-img-wrap">
                        @if($r->image)
                            <img src="{{ $r->image_url }}" alt="{{ $r->title }}" class="blog-card-img">
                        @else
                            <div class="blog-card-img-placeholder"><i class="fas fa-feather-alt"></i></div>
                        @endif
                        @if($r->category)
                            <a href="{{ route('blog.category', $r->category->slug) }}" class="blog-badge">{{ $r->category->name }}</a>
                        @endif
                    </div>
                    <div class="blog-card-body">
                        <h3 class="blog-card-title">{{ $r->title }}</h3>
                        <span class="blog-read-more">Read More <i class="fas fa-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@section('styles')
<link href="{{ asset('css/blog.css') }}" rel="stylesheet">
@endsection
