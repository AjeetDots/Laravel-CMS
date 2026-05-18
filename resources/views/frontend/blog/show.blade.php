@extends('layouts.frontend')
@section('title', $post->title)
@section('body_class', 'nav-solid page-blog')
@section('content')

<section class="finishes-intro">
    <div class="container">
        @if($post->postCategory)
            <a href="{{ route('blog.category', $post->postCategory->slug) }}"
               class="finishes-intro__eyebrow finishes-intro__eyebrow--link">{{ $post->postCategory->name }}</a>
        @else
            <span class="finishes-intro__eyebrow">Journal</span>
        @endif
        <h1 class="finishes-intro__title">{{ $post->title }}</h1>
        <div class="finishes-intro__blog-meta">
            @if($post->author)<span><i class="fas fa-user me-1" aria-hidden="true"></i>{{ $post->author }}</span>@endif
            @if($post->published_at)<span><i class="far fa-calendar me-1" aria-hidden="true"></i>{{ $post->published_at->format('d F Y') }}</span>@endif
            <span><i class="far fa-clock me-1" aria-hidden="true"></i>{{ $post->reading_time }}</span>
        </div>
        <nav class="finishes-intro__breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                @if($post->postCategory)
                    <li class="breadcrumb-item">
                        <a href="{{ route('blog.category', $post->postCategory->slug) }}">{{ $post->postCategory->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Main + sidebar — same structure as finish / service detail --}}
<section class="section section-white editorMain">
    <div class="container">
        <div class="row g-5">

            <div class="col-lg-8 editorInner">
                @if($post->image)
                    <div class="media-frame media-frame--short service-detail-lead-img mb-4">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                    </div>
                @endif

                <div class="service-detail-body blog-post-content">
                    {!! $post->content !!}
                </div>

                @if($post->postCategory)
                <div class="mt-4 pt-3" style="border-top:1px solid var(--border);">
                    <span style="font-size:.83rem;color:var(--ink-light);font-weight:500;text-transform:uppercase;letter-spacing:.08em;">Filed under</span>
                    <a href="{{ route('blog.category', $post->postCategory->slug) }}" class="blog-cat-tag ms-2">
                        <i class="fas fa-folder me-1" aria-hidden="true"></i>{{ $post->postCategory->name }}
                    </a>
                </div>
                @endif

                <div class="mt-4">
                    <a href="{{ route('blog.index') }}" class="btn-outline-site">
                        <i class="fas fa-arrow-left me-2" aria-hidden="true"></i>Back to Blog
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="service-sidebar-wrap">
                    @if($post->postCategory)
                    <div class="service-sidebar-card mb-4">
                        <span class="eyebrow">Category</span>
                        <h4>{{ $post->postCategory->name }}</h4>
                        <p class="sub mb-0">Posts filed under this topic.</p>
                        <a href="{{ route('blog.category', $post->postCategory->slug) }}" class="btn-outline-site w-100 justify-content-center mt-3">
                            View category <i class="fas fa-arrow-right" style="font-size:.75rem;" aria-hidden="true"></i>
                        </a>
                    </div>
                    @endif

                    @if($latestPosts->count())
                    <div class="service-sidebar-card">
                        <span class="eyebrow">Latest</span>
                        <h4>From the journal</h4>
                        <ul class="sidebar-post-list">
                            @foreach($latestPosts as $lp)
                            <li class="sidebar-post-item">
                                <a href="{{ route('blog.show', $lp->slug) }}" class="sidebar-post-link">
                                    @if($lp->image)
                                        <img src="{{ $lp->image_url }}" alt="{{ $lp->title }}" class="sidebar-post-thumb">
                                    @else
                                        <div class="sidebar-post-thumb-placeholder"><i class="fas fa-feather-alt" aria-hidden="true"></i></div>
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
    </div>
</section>

@if($related->count())
<section class="section section-soft">
    <div class="container">
        <div class="row align-items-end mb-4">
            <div class="col">
                <span class="finishes-intro__eyebrow">More to read</span>
                <h2 class="h3 mb-0" style="font-family:Georgia,'Times New Roman',Times,serif;">Related posts</h2>
            </div>
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
                        @if($r->postCategory)
                            <a href="{{ route('blog.category', $r->postCategory->slug) }}" class="blog-badge">{{ $r->postCategory->name }}</a>
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
