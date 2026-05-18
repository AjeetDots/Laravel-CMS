@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid page-cms page-cms--full')

@section('content')
@php
    $hasIntro = trim((string) ($page->content ?? '')) !== '';
    $hasBlocks = $page->sections->count() > 0;
@endphp

<div class="cms-page-skin cms-page-skin--full">
    @include('frontend.pages.partials.cms-page-skin-styles', ['variant' => 'full'])

    @include('frontend.pages.partials.cms-finishes-hero', ['page' => $page])

    <section class="cms-page-body" id="main-content">
        <div class="cms-builder innerGap">
            <div class="container cms-page-container cms-builder__shell">
                @if($hasIntro || $hasBlocks)
                    @include('frontend.pages.partials.cms-body-inner', ['page' => $page, 'layout' => 'full'])
                @else
                    <div class="cms-page-empty" role="status">
                        <p class="mb-2 fw-medium text-secondary-emphasis">This page is ready for your content.</p>
                        <p class="mb-0 small">Add <strong>Page header</strong>, <strong>Main content</strong>, or <strong>Sections</strong> in the admin.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
