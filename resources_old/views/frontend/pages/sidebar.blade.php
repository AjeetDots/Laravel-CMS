@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('content')
    @include('frontend.pages.partials.cms-hero', ['page' => $page])

    <section class="section section-white cms-page-section cms-page-section--sidebar">
        <div class="container">
            <div class="row g-4 g-xl-5 align-items-start justify-content-center">
                <div class="col-12 col-lg-8">
                    @include('frontend.pages.partials.cms-body-inner', ['page' => $page])
                </div>
                <div class="col-12 col-lg-4 col-xl-3">
                    @include('frontend.pages.partials.cms-sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection
