@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid page-cms page-cms--sidebar')

@section('content')
@php
    $hasIntro = trim((string) ($page->content ?? '')) !== '';
    $hasBlocks = $page->sections->count() > 0;
@endphp

<div class="cms-page-skin cms-page-skin--sidebar">
    @include('frontend.pages.partials.cms-page-skin-styles', ['variant' => 'sidebar'])

    @include('frontend.pages.partials.cms-finishes-hero', ['page' => $page])

    <section class="cms-page-body" id="main-content">
        <div class="cms-builder cms-builder--sidebar">
            <div class="container cms-builder__shell cms-builder__shell--sidebar">
                <div class="row g-4 g-xl-5 align-items-start cms-builder-sidebar__grid">
                    <main class="col-12 col-lg-8 cms-builder-main order-2 order-lg-1">
                        @if($hasIntro || $hasBlocks)
                            @include('frontend.pages.partials.cms-body-inner', ['page' => $page, 'layout' => 'sidebar'])
                        @else
                            <div class="cms-page-empty" role="status">
                                <p class="mb-2 fw-medium text-secondary-emphasis">This page is ready for your content.</p>
                                <p class="mb-0 small">Add <strong>Page header</strong>, <strong>Main content</strong>, or <strong>Sections</strong> in the admin.</p>
                            </div>
                        @endif
                    </main>
                    <div class="col-12 col-lg-4 col-xl-3 cms-builder-aside order-1 order-lg-2">
                        @include('frontend.pages.partials.cms-sidebar')
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
