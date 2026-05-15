@php
    $hasIntro = trim((string) ($page->content ?? '')) !== '';
    $hasBlocks = $page->sections->count() > 0;
    $sectionsFirst = ($page->body_order ?? \App\Models\Page::BODY_ORDER_CONTENT_FIRST) === \App\Models\Page::BODY_ORDER_SECTIONS_FIRST;
    $layoutKey = $layout ?? 'default';
    $isBuilderLayout = in_array($layoutKey, ['full', 'sidebar'], true);
    $sectionLayout = $isBuilderLayout ? $layoutKey : $layoutKey;
@endphp

@if($sectionsFirst)
    @if($hasBlocks)
        <div class="{{ $isBuilderLayout ? 'cms-builder__sections' : 'cms-page-blocks' }}">
            @foreach($page->sections as $section)
                @if($isBuilderLayout)
                    <section class="cms-builder-band{{ $loop->even ? ' cms-builder-band--alt' : '' }}" aria-label="Section {{ $loop->iteration }}">
                @endif
                <div class="row align-items-center g-4 g-xl-5 {{ $isBuilderLayout ? 'cms-builder-band__grid' : 'cms-page-block-row' }}">
                    @include('frontend.sections.'.$section->type, ['data' => $section->data, 'layout' => $sectionLayout, 'bandIndex' => $loop->iteration])
                </div>
                @if($isBuilderLayout)
                    </section>
                @endif
            @endforeach
        </div>
    @endif
    @if($hasIntro)
        <article class="cms-page-article page-content cms-page-intro {{ $isBuilderLayout ? 'cms-builder__lead cms-builder__lead--after-sections' : '' }} {{ ! $isBuilderLayout && $hasBlocks ? 'mt-4 mt-lg-5 pt-lg-2' : '' }}">
            {!! $page->content !!}
        </article>
    @endif
@else
    @if($hasIntro)
        <article class="cms-page-article page-content cms-page-intro {{ $isBuilderLayout ? 'cms-builder__lead' : '' }}">
            {!! $page->content !!}
        </article>
    @endif
    @if($hasBlocks)
        <div class="{{ $isBuilderLayout ? 'cms-builder__sections cms-builder__sections--after-lead' : 'cms-page-blocks' }} {{ ! $isBuilderLayout && $hasIntro ? 'mt-4 mt-lg-5 pt-lg-2' : '' }}">
            @foreach($page->sections as $section)
                @if($isBuilderLayout)
                    <section class="cms-builder-band{{ $loop->even ? ' cms-builder-band--alt' : '' }}" aria-label="Section {{ $loop->iteration }}">
                @endif
                <div class="row align-items-center g-4 g-xl-5 {{ $isBuilderLayout ? 'cms-builder-band__grid' : 'cms-page-block-row' }}">
                    @include('frontend.sections.'.$section->type, ['data' => $section->data, 'layout' => $sectionLayout, 'bandIndex' => $loop->iteration])
                </div>
                @if($isBuilderLayout)
                    </section>
                @endif
            @endforeach
        </div>
    @endif
@endif

@if(! $hasIntro && ! $hasBlocks)
    <div class="cms-page-placeholder text-center text-muted py-5 px-3 {{ $isBuilderLayout ? 'cms-builder__empty' : '' }}">
        <p class="mb-2 fs-5 fw-medium text-secondary-emphasis">This page is ready for your content.</p>
        <p class="mb-0 small {{ $isBuilderLayout ? '' : 'col-md-8 mx-auto' }}">Add <strong>main content</strong> and/or <strong>sections</strong> in the admin to build out this page—no code required.</p>
    </div>
@endif
