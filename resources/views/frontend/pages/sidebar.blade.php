@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('content')
    <div class="cms-page-layout cms-page-layout--sidebar">
        @include('frontend.pages.partials.cms-hero', ['page' => $page])

        <div class="cms-builder cms-builder--sidebar">
            <div class="container cms-builder__shell cms-builder__shell--sidebar">
                <div class="row g-4 g-xl-5 align-items-start cms-builder-sidebar__grid">
                    <main class="col-12 col-lg-8 cms-builder-main order-2 order-lg-1">
                        @include('frontend.pages.partials.cms-body-inner', ['page' => $page, 'layout' => 'sidebar'])
                    </main>
                    <div class="col-12 col-lg-4 col-xl-3 cms-builder-aside order-1 order-lg-2">
                        @include('frontend.pages.partials.cms-sidebar')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
