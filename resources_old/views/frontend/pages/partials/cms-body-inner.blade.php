@php
    $hasIntro = trim((string) ($page->content ?? '')) !== '';
    $hasBlocks = $page->sections->count() > 0;
    $sectionsFirst = ($page->body_order ?? \App\Models\Page::BODY_ORDER_CONTENT_FIRST) === \App\Models\Page::BODY_ORDER_SECTIONS_FIRST;
@endphp

@if($sectionsFirst)
    @if($hasBlocks)
        <div class="cms-page-blocks">
            @foreach($page->sections as $section)
                <div class="row align-items-start g-4 g-lg-5 cms-page-block-row">
                    @include('frontend.sections.'.$section->type, ['data' => $section->data])
                </div>
            @endforeach
        </div>
    @endif
    @if($hasIntro)
        <article class="cms-page-article page-content cms-page-intro {{ $hasBlocks ? 'mt-4 mt-lg-5 pt-lg-2' : '' }}">
            {!! $page->content !!}
        </article>
    @endif
@else
    @if($hasIntro)
        <article class="cms-page-article page-content cms-page-intro">
            {!! $page->content !!}
        </article>
    @endif
    @if($hasBlocks)
        <div class="cms-page-blocks {{ $hasIntro ? 'mt-4 mt-lg-5 pt-lg-2' : '' }}">
            @foreach($page->sections as $section)
                <div class="row align-items-start g-4 g-lg-5 cms-page-block-row">
                    @include('frontend.sections.'.$section->type, ['data' => $section->data])
                </div>
            @endforeach
        </div>
    @endif
@endif

@if(! $hasIntro && ! $hasBlocks)
    <div class="cms-page-placeholder text-center text-muted py-5 px-3">
        <p class="mb-2 fs-5 fw-medium text-secondary-emphasis">This page is ready for your content.</p>
        <p class="mb-0 small col-md-8 mx-auto">Add <strong>main content</strong> and/or <strong>sections</strong> in the admin to build out this page—no code required.</p>
    </div>
@endif
