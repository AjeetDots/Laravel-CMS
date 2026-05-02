@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('content')
@php
    $heroEyebrows = [
        'faq' => 'Help center',
        'docs' => 'Documentation',
        'help' => 'Support',
    ];
    $heroEyebrow = $heroEyebrows[$page->slug] ?? 'Overview';
@endphp

<div class="page-hero">
    <div class="container">
        <span class="eyebrow">{{ $heroEyebrow }}</span>
        <h1 class="page-hero-title-wide">{{ $page->title }}</h1>
        @if($page->meta_description && \Illuminate\Support\Str::length($page->meta_description) < 220)
            <p>{{ $page->meta_description }}</p>
        @endif
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">{{ $page->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section section-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                @if($page->content)
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                @else
                    <div class="text-center py-5" style="color:var(--ink-light);">
                        <i class="fas fa-file-alt fa-3x mb-3" style="color:var(--border);"></i>
                        <p class="mb-0">Content coming soon.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
