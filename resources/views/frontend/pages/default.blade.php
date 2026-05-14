@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('content')
    <div class="cms-page-default-centered">
        @include('frontend.pages.partials.cms-hero', ['page' => $page])

        <section class="section section-white cms-page-section cms-page-section--default">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10 col-xl-9">
                        @include('frontend.pages.partials.cms-body-inner', ['page' => $page])
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
