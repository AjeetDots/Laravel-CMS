@extends('layouts.frontend')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description ?? '')

@section('body_class', 'nav-solid')

@section('content')
    <div class="cms-page-layout cms-page-layout--full">
        @include('frontend.pages.partials.cms-hero', ['page' => $page])

        <div class="cms-builder">
            <div class="container-fluid cms-page-container cms-builder__shell px-3 px-sm-4 px-xl-5">
                @include('frontend.pages.partials.cms-body-inner', ['page' => $page, 'layout' => 'full'])
            </div>
        </div>
    </div>
@endsection
