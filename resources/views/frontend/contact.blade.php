@extends('layouts.frontend')

@section('title', isset($page) ? (trim((string) ($page->meta_title ?? '')) !== '' ? $page->meta_title : $page->title) : 'Contact')

@section('meta_description', isset($page) ? ($page->meta_description ?? '') : '')

@section('body_class', 'nav-solid page-contact')

@section('content')
@include('frontend.partials.contact-page-sections', [
    'phoneCountries' => $phoneCountries ?? null,
    'contactPage' => $contactPage ?? null,
    'contactHeroUrl' => $contactHeroUrl ?? null,
])
@endsection
