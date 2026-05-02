{{--
    Frontend SEO meta-tags partial.

    Usage in a frontend controller/view:
        @include('partials.meta-tags', ['model' => $post])   // model uses HasSeo trait
        @include('partials.meta-tags')                       // falls back to @yield values

    The partial also picks up @yield('title'), @yield('meta_description'),
    @yield('og_image'), and @yield('canonical') so plain views that don't pass
    a model still get correct output.
--}}
@php
    $model     = $model ?? null;
    $siteName  = $settings->get('site_name', config('app.name'));
    $siteTagline = $settings->get('site_tagline', '');

    // ── Core values (model → yield fallback → site default) ────────────────
    $pageTitle  = $model ? $model->getSeoTitle()       : null;
    $pageTitle  = $pageTitle ?: trim(View::yieldContent('title')) ?: $siteName;

    $pageDesc   = $model ? $model->getSeoDescription() : null;
    $pageDesc   = $pageDesc ?: trim(View::yieldContent('meta_description')) ?: $siteTagline;

    $canonical  = $model ? $model->getSeoCanonical()   : null;
    $canonical  = $canonical ?: trim(View::yieldContent('canonical')) ?: url()->current();

    $robots     = $model ? $model->getSeoRobots()      : 'index, follow';
    $keyword    = $model ? $model->getSeoKeyword()     : '';

    $seo        = $model?->seoMeta;

    // ── Open Graph ──────────────────────────────────────────────────────────
    $ogTitle    = $seo?->og_title            ?: $pageTitle;
    $ogDesc     = $seo?->og_description      ?: $pageDesc;
    $ogImageRaw = $seo?->og_image            ?: ($model?->getSeoImage() ?? null);
    $ogImage    = $ogImageRaw ?: trim(View::yieldContent('og_image'));

    // ── Twitter Card ────────────────────────────────────────────────────────
    $twCard  = $seo?->twitter_card           ?? 'summary_large_image';
    $twTitle = $seo?->twitter_title          ?: $ogTitle;
    $twDesc  = $seo?->twitter_description    ?: $ogDesc;
    $twImage = $seo?->twitter_image          ?: $ogImage;

    $twitterHandle = $settings->get('twitter_handle', '');
@endphp

{{-- ── Core ──────────────────────────────────────────────────────────────── --}}
<title>{{ $pageTitle }} — {{ $siteName }}</title>
<meta name="description" content="{{ $pageDesc }}">
@if($keyword)
<meta name="keywords" content="{{ $keyword }}">
@endif
<meta name="robots" content="{{ $robots }}">
<link rel="canonical" href="{{ $canonical }}">

{{-- ── Open Graph ──────────────────────────────────────────────────────────── --}}
<meta property="og:type"        content="website">
<meta property="og:site_name"   content="{{ $siteName }}">
<meta property="og:url"         content="{{ $canonical }}">
<meta property="og:title"       content="{{ $ogTitle }}">
<meta property="og:description" content="{{ $ogDesc }}">
@if($ogImage)
<meta property="og:image"       content="{{ $ogImage }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
@endif

{{-- ── Twitter Card ─────────────────────────────────────────────────────────── --}}
<meta name="twitter:card"        content="{{ $twCard }}">
@if($twitterHandle)
<meta name="twitter:site"        content="{{ $twitterHandle }}">
@endif
<meta name="twitter:title"       content="{{ $twTitle }}">
<meta name="twitter:description" content="{{ $twDesc }}">
@if($twImage)
<meta name="twitter:image"       content="{{ $twImage }}">
@endif

{{-- ── JSON-LD Structured Data ──────────────────────────────────────────────── --}}
@if($seo?->schema_markup)
<script type="application/ld+json">{!! $seo->schema_markup !!}</script>
@endif
