@extends('layouts.frontend')
@section('title', $post->title)
@section('body_class', 'nav-solid')
@section('content')

<div class="page-hero">
    <div class="container">
        @if($post->category)
            <span class="eyebrow">{{ $post->category }}</span>
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
                <li class="breadcrumb-item active">{{ Str::limit($post->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if($post->image)
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                         style="width:100%;border-radius:8px;margin-bottom:40px;max-height:480px;object-fit:cover;">
                @endif
                <div class="blog-post-content">
                    {!! nl2br(e($post->content)) !!}
                </div>
                <div class="mt-5 pt-4" style="border-top:1px solid var(--border);">
                    <a href="{{ route('blog.index') }}" class="btn-outline-site">
                        <i class="fas fa-arrow-left me-2"></i>Back to Blog
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

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
                        @if($r->category)<span class="blog-badge">{{ $r->category }}</span>@endif
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
