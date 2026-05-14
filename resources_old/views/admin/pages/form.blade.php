@extends('layouts.admin')

@section('title', isset($page->id) ? 'Edit Page' : 'Create Page')

@section('styles')
<style>
    .section-collapse-toggle.collapsed .section-item-chevron,
    .cms-panel-toggle.collapsed .section-item-chevron {
        transform: rotate(-90deg);
    }
    .section-item-chevron {
        transition: transform 0.2s ease;
        display: inline-block;
    }
    .section-collapse-toggle:focus-visible,
    .cms-panel-toggle:focus-visible {
        box-shadow: inset 0 0 0 2px rgba(13, 110, 253, 0.35);
        z-index: 1;
    }
    .cms-sections-header-add {
        white-space: nowrap;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    .cms-page-form-actions {
        top: 1rem;
        z-index: 1010;
    }
</style>
@endsection

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($page->id) ? 'Edit Page' : 'Create Page' }}</h1>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<form action="{{ isset($page->id) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($page->id)) @method('PUT') @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span>Page Content</span>
                    <button type="button"
                            class="btn btn-sm btn-outline-secondary border-0 px-2"
                            id="pageBuildHelpBtn"
                            aria-label="How to use this page editor">
                        <i class="fas fa-circle-info text-primary" aria-hidden="true"></i>
                        <span class="visually-hidden">How to use this page editor</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" id="titleInput" class="form-control"
                               value="{{ old('title', $page->title) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="slugInput" class="form-control"
                               value="{{ old('slug', $page->slug) }}" placeholder="auto-generated if empty">
                    </div>
                    <div class="mb-3" data-cms-sectioned-panel>
                        <div class="card border shadow-sm overflow-hidden">
                            <div class="card-header p-0 bg-light border-bottom">
                                <button type="button"
                                        class="btn btn-link w-100 text-start text-decoration-none py-2 px-3 rounded-0 d-flex align-items-center gap-2 cms-panel-toggle"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapsePageMainContent"
                                        aria-expanded="true"
                                        aria-controls="collapsePageMainContent">
                                    <i class="fas fa-chevron-down section-item-chevron text-muted small" aria-hidden="true"></i>
                                    <span class="fw-semibold text-dark">Main content</span>
                                </button>
                            </div>
                            <div id="collapsePageMainContent" class="collapse show">
                                <fieldset id="pageMainContentFieldset" class="border-0 p-0 m-0 w-100" data-cms-sectioned-only>
                                    <legend class="visually-hidden">Main page content</legend>
                                    <div class="card-body border-0 pt-0">
                                        <p class="text-muted small mb-2">Optional rich text for intros, policies, or long copy. Whether it appears above or below <strong>Sections</strong> on the live site is controlled by <strong>Content order</strong> in Settings.</p>
                                        <textarea name="content" id="postContent" class="form-control wysiwyg" rows="10"
                                                  placeholder="e.g. Introduction, headings, lists, images…">{{ old('content', $page->content) }}</textarea>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" data-cms-sectioned-panel>
                        <div class="card border shadow-sm overflow-hidden">
                            <div class="card-header p-0 bg-light border-bottom">
                                <div class="d-flex align-items-stretch flex-nowrap">
                                    <button type="button"
                                            class="btn btn-link flex-grow-1 text-start text-decoration-none py-2 px-3 rounded-0 d-flex align-items-center gap-2 cms-panel-toggle"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapsePageSections"
                                            aria-expanded="true"
                                            aria-controls="collapsePageSections">
                                        <i class="fas fa-chevron-down section-item-chevron text-muted small" aria-hidden="true"></i>
                                        <span class="fw-semibold text-dark">Sections</span>
                                    </button>
                                    <button type="button"
                                            id="addSection"
                                            class="btn btn-primary rounded-0 border-0 border-start px-3 cms-sections-header-add d-flex align-items-center">
                                        <i class="fas fa-plus me-2 d-none d-sm-inline" aria-hidden="true"></i>Add section
                                    </button>
                                </div>
                            </div>
                            <div id="collapsePageSections" class="collapse show">
                                <fieldset id="pageSectionsFieldset" class="border-0 p-0 m-0 w-100" data-cms-sectioned-only>
                                    <legend class="visually-hidden">Page sections</legend>
                                    <div class="card-body border-0 pt-0">
                                        <div id="sectionsWrapper">
                                            @foreach($page->sections ?? [] as $index => $section)
                                                @php
                                                    $data = $section->data;
                                                @endphp
                                                @include('admin.pages.partials.section', [
                                                    'index' => $index,
                                                    'data' => $data,
                                                ])
                                            @endforeach
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="pageSidebarPanel">
                        <div class="card border border-secondary border-opacity-25 shadow-sm overflow-hidden">
                            <div class="card-header p-0 bg-light border-bottom">
                                <button type="button"
                                        class="btn btn-link w-100 text-start text-decoration-none py-2 px-3 rounded-0 d-flex align-items-center justify-content-between gap-2 cms-panel-toggle"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapsePageSidebar"
                                        aria-expanded="true"
                                        aria-controls="collapsePageSidebar">
                                    <span class="d-flex align-items-center gap-2 min-w-0">
                                        <i class="fas fa-chevron-down section-item-chevron text-muted small flex-shrink-0" aria-hidden="true"></i>
                                        <span class="fw-semibold text-dark text-truncate">Sidebar &amp; contact card</span>
                                    </span>
                                    <span class="badge text-bg-light border flex-shrink-0">With sidebar only</span>
                                </button>
                            </div>
                            <div id="collapsePageSidebar" class="collapse show">
                                <fieldset id="pageSidebarContentFieldset" class="border-0 p-0 m-0 w-100" data-cms-sidebar-only>
                                    <legend class="visually-hidden">Sidebar content</legend>
                                    <div class="card-body border-0 pt-0">
                                        <p class="text-muted small mb-3">Shown in the <strong>right column</strong> when <strong>With sidebar</strong> is selected.</p>
                                        <div class="mb-3">
                                            <label class="form-label" for="sidebarCtaTitleInput">Contact card heading</label>
                                            <input type="text" name="sidebar_cta_title" id="sidebarCtaTitleInput" class="form-control" maxlength="200"
                                                   value="{{ old('sidebar_cta_title', $page->sidebar_cta_title) }}"
                                                   placeholder="e.g. Get in touch">
                                            <div class="form-text small">Leave empty to hide this heading on the site.</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="sidebarCtaTextInput">Contact card intro</label>
                                            <textarea name="sidebar_cta_text" id="sidebarCtaTextInput" class="form-control" rows="3" maxlength="600"
                                                      placeholder="e.g. Short line inviting visitors to reach out…">{{ old('sidebar_cta_text', $page->sidebar_cta_text) }}</textarea>
                                            <div class="form-text small">Plain text under the heading. Leave empty to hide on the site.</div>
                                        </div>
                                        <hr class="my-3 text-secondary opacity-25">
                                        <p class="text-muted small mb-2"><strong>Optional</strong> rich block above the contact card (promos, links, etc.).</p>
                                        <textarea name="sidebar_content" id="sidebarContentInput" class="form-control wysiwyg" rows="8"
                                                  placeholder="e.g. Optional sidebar promo, bullets, links…">{{ old('sidebar_content', $page->sidebar_content) }}</textarea>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.partials.seo-panel', [
                'seo'            => $page->seoMeta ?? null,
                'titleFieldId'   => 'titleInput',
                'slugFieldId'    => 'slugInput',
                'contentFieldId' => 'postContent',
            ])
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">Settings</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label d-flex align-items-center gap-2">
                            Template
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary border-0 px-1 py-0 lh-1"
                                    id="pageTemplateHelpBtn"
                                    aria-label="Template types (hover or click for help)"
                                    title="Template types — hover or click">
                                <i class="fas fa-circle-info text-primary" aria-hidden="true"></i>
                                <span class="visually-hidden">Template types — hover or click for help</span>
                            </button>
                        </label>
                        <select name="template" id="pageTemplateSelect" class="form-select">
                            @foreach($templates as $value => $label)
                                <option value="{{ $value }}" {{ old('template', $page->template ?? 'default') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex align-items-center gap-2 mb-1">
                            Content order
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary border-0 px-1 py-0 lh-1"
                                    id="pageOrderHelpBtn"
                                    aria-label="About content order">
                                <i class="fas fa-circle-info text-primary" aria-hidden="true"></i>
                                <span class="visually-hidden">About content order</span>
                            </button>
                        </label>
                        <select name="body_order" id="pageBodyOrderSelect" class="form-select">
                            <option value="{{ \App\Models\Page::BODY_ORDER_CONTENT_FIRST }}" {{ old('body_order', $page->body_order ?? \App\Models\Page::BODY_ORDER_CONTENT_FIRST) === \App\Models\Page::BODY_ORDER_CONTENT_FIRST ? 'selected' : '' }}>
                                Main content first, then sections
                            </option>
                            <option value="{{ \App\Models\Page::BODY_ORDER_SECTIONS_FIRST }}" {{ old('body_order', $page->body_order ?? \App\Models\Page::BODY_ORDER_CONTENT_FIRST) === \App\Models\Page::BODY_ORDER_SECTIONS_FIRST ? 'selected' : '' }}>
                                Sections first, then main content
                            </option>
                        </select>
                        <p class="form-text small mb-0">Applies to Default, Full width, and With sidebar templates on the public site.</p>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Published</label>
                    </div>
                </div>
            </div>
            <div class="card mb-4 shadow-sm sticky-lg-top cms-page-form-actions">
                <div class="card-body d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ isset($page->id) ? 'Update Page' : 'Create Page' }}
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
            {{-- Legacy fields kept for backwards compatibility --}}
            <input type="hidden" name="meta_title"       value="{{ old('seo.meta_title',       $page->seoMeta?->meta_title       ?? $page->meta_title) }}">
            <input type="hidden" name="meta_description" value="{{ old('seo.meta_description', $page->seoMeta?->meta_description ?? $page->meta_description) }}">
        </div>
    </div>
</form>

<div id="pageBuildHelpPopoverHtml" class="d-none">
    <ul class="small mb-0 ps-3 text-start">
        <li class="mb-2"><strong>Title</strong> — Page headline and default browser tab title (override in SEO if needed).</li>
        <li class="mb-2"><strong>Slug</strong> — Part of the URL. Leave empty to auto-build from the title.</li>
        <li class="mb-2"><strong>Main content</strong> — Optional rich text (intro, policy, long copy). Use the editor toolbar for headings, lists, links, and images.</li>
        <li class="mb-2"><strong>Sections</strong> — Add one or more blocks with title, body, optional image, and buttons—good for story-style layouts.</li>
        <li class="mb-2"><strong>Content order</strong> — In Settings, choose whether main content or sections appears first on the live page.</li>
        <li class="mb-2"><strong>Template</strong> — Default, Full width, or With sidebar controls layout (use the info icon beside Template for more).</li>
        <li class="mb-2"><strong>Sidebar content</strong> — When <strong>With sidebar</strong> is selected: optional rich block, plus editable <strong>Contact card heading</strong> and <strong>intro</strong> for the grey contact panel (phone/email still come from site settings).</li>
        <li><strong>SEO Analysis</strong> — Expand to tune search snippets, then save. Use <strong>Published</strong> to show or hide the page.</li>
    </ul>
</div>

<div id="pageOrderHelpPopoverHtml" class="d-none">
    <p class="small mb-2 text-start">Controls the vertical order of the two content areas on <strong>Default</strong>, <strong>Full width</strong>, and <strong>With sidebar</strong> templates only.</p>
    <ul class="small mb-0 ps-3 text-start">
        <li class="mb-1"><strong>Main content first</strong> — Intro or full article at the top, then section blocks below.</li>
        <li><strong>Sections first</strong> — Feature blocks at the top, then main content (e.g. closing text or legal notes).</li>
    </ul>
</div>

<div id="pageTemplateHelpPopoverHtml" class="d-none">
    <ul class="small mb-0 ps-3 text-start">
        <li class="mb-2"><strong>Default</strong> — Main story in a centred column; best for articles, policies, FAQs.</li>
        <li class="mb-2"><strong>Full width</strong> — Wider main column; use for long guides or when you want more horizontal room.</li>
        <li class="mb-2"><strong>With sidebar</strong> — Main column + sidebar. Optional rich <strong>Sidebar content</strong>; contact card heading/intro are editable; phone, email, and “Contact us” use site settings.</li>
        <li><strong>About Page (Editorial)</strong> — Only offered on the About Us page; uses the dedicated About layout and theme options instead of the main/section builders.</li>
    </ul>
</div>

<template id="sectionTemplate">

    <div class="section-item card mb-3 border shadow-sm">
        <div class="card-header p-0 bg-light border-bottom">
            <div class="d-flex align-items-stretch flex-nowrap">
                <button type="button"
                        class="btn btn-link flex-grow-1 text-start text-decoration-none py-2 px-3 rounded-0 section-collapse-toggle d-flex align-items-center gap-2"
                        data-bs-toggle="collapse"
                        data-bs-target="#sectionCollapse-placeholder"
                        aria-expanded="true"
                        aria-controls="sectionCollapse-placeholder">
                    <i class="fas fa-chevron-down section-item-chevron text-muted small" aria-hidden="true"></i>
                    <span class="fw-semibold text-dark section-item-index-label">Section</span>
                </button>
                <button type="button"
                        class="btn btn-outline-danger remove-section rounded-0 border-0 border-start px-3"
                        title="Remove this section">
                    <i class="fas fa-trash-alt" aria-hidden="true"></i>
                    <span class="visually-hidden">Remove section</span>
                </button>
            </div>
        </div>
        <div class="collapse show section-item-collapse">
            <div class="card-body">
                <input type="hidden" class="position-field">
                <input type="hidden" class="type-field" value="media_content">

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" class="form-control title-field">
                </div>

                <div class="mb-3">
                    <label>Content</label>
                    <textarea class="form-control content-field wysiwyg" rows="8"></textarea>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Image</label>
                        <input
                            type="file"
                            class="form-control image-field">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Position</label>
                        <select class="form-control image-position-field">
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Buttons</label>
                    <div class="buttons-wrapper"></div>
                    <button
                        type="button"
                        class="btn btn-sm btn-secondary add-button">
                        + Add Button
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

@endsection
@push('scripts')
<script>

    const sectionTemplates = @json(\App\Models\Page::sectionedTemplates());
    const templateSelect = document.getElementById('pageTemplateSelect');
    const sectionedFieldsets = document.querySelectorAll('[data-cms-sectioned-only]');
    const sectionedPanels = document.querySelectorAll('[data-cms-sectioned-panel]');
    const bodyOrderSelect = document.getElementById('pageBodyOrderSelect');
    const sidebarContentFieldset = document.getElementById('pageSidebarContentFieldset');
    const sidebarPanel = document.getElementById('pageSidebarPanel');

    function initHelpPopover(btnId, htmlSourceId, title, popoverOptions) {
        const btn = document.getElementById(btnId);
        const src = document.getElementById(htmlSourceId);
        if (!btn || !src || typeof bootstrap === 'undefined' || !bootstrap.Popover) {
            return;
        }
        if (btn.dataset.popoverBound === '1') {
            return;
        }
        btn.dataset.popoverBound = '1';
        const defaults = {
            title: title,
            html: true,
            sanitize: false,
            content: src.innerHTML,
            trigger: 'focus',
            placement: 'bottom',
            container: 'body',
        };
        new bootstrap.Popover(btn, Object.assign({}, defaults, popoverOptions || {}));
    }

    function isInsideDisabledCmsFieldset(el) {
        for (let i = 0; i < sectionedFieldsets.length; i++) {
            const fs = sectionedFieldsets[i];
            if (fs.contains(el) && fs.disabled) {
                return true;
            }
        }
        if (sidebarContentFieldset && sidebarContentFieldset.contains(el) && sidebarContentFieldset.disabled) {
            return true;
        }
        return false;
    }

    function syncPageSectionsForTemplate() {
        if (!templateSelect) {
            return;
        }
        const show = sectionTemplates.indexOf(templateSelect.value) !== -1;
        sectionedPanels.forEach(function(panel) {
            panel.style.display = show ? '' : 'none';
        });
        sectionedFieldsets.forEach(function(fs) {
            fs.disabled = !show;
        });
        if (bodyOrderSelect) {
            bodyOrderSelect.disabled = !show;
        }
        if (show) {
            sectionedFieldsets.forEach(function(fs) {
                fs.querySelectorAll('.wysiwyg').forEach(function(el) {
                    initEditor(el);
                });
                fs.querySelectorAll('.section-item').forEach(function(section) {
                    bindButtons(section);
                });
            });
        }
        if (sidebarContentFieldset && sidebarPanel) {
            const showSidebarTemplate = templateSelect.value === 'sidebar';
            sidebarPanel.style.display = showSidebarTemplate ? '' : 'none';
            sidebarContentFieldset.disabled = !showSidebarTemplate;
            if (showSidebarTemplate) {
                sidebarContentFieldset.querySelectorAll('.wysiwyg').forEach(function(el) {
                    initEditor(el);
                });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function(){
        syncPageSectionsForTemplate();
        if (templateSelect) {
            templateSelect.addEventListener('change', syncPageSectionsForTemplate);
        }
        document.querySelectorAll('.wysiwyg').forEach(function(el){
            if (isInsideDisabledCmsFieldset(el)) {
                return;
            }
            initEditor(el);
        });
        initHelpPopover('pageBuildHelpBtn', 'pageBuildHelpPopoverHtml', 'How to build this page');
        initHelpPopover('pageOrderHelpBtn', 'pageOrderHelpPopoverHtml', 'Content order');
        initHelpPopover('pageTemplateHelpBtn', 'pageTemplateHelpPopoverHtml', 'Page templates', {
            trigger: 'hover focus',
            delay: { show: 120, hide: 200 },
        });
    });

    let index = {{ $page->sections->count() ?? 0 }};

    const wrapper = document.getElementById(
        'sectionsWrapper'
    );


    document.getElementById(
        'addSection'
    ).addEventListener(
        'click',
        addSection
    );


    function addSection()
    {
        const sectionsCollapse = document.getElementById('collapsePageSections');
        if (sectionsCollapse && typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
            bootstrap.Collapse.getOrCreateInstance(sectionsCollapse).show();
        }

        let currentIndex = index;
        const fragment = document.getElementById('sectionTemplate').content.cloneNode(true);
        const section = fragment.querySelector('.section-item');
        if (!section) {
            return;
        }

        const collapseId = 'sectionCollapse-' + currentIndex;
        const collapseEl = section.querySelector('.section-item-collapse');
        if (collapseEl) {
            collapseEl.id = collapseId;
        }
        const toggler = section.querySelector('.section-collapse-toggle');
        if (toggler) {
            toggler.setAttribute('data-bs-target', '#' + collapseId);
            toggler.setAttribute('aria-controls', collapseId);
        }
        const idxLabel = section.querySelector('.section-item-index-label');
        if (idxLabel) {
            idxLabel.textContent = 'Section ' + (currentIndex + 1);
        }

        section.querySelector('.type-field').name = `sections[${currentIndex}][type]`;
        section.querySelector('.title-field').name = `sections[${currentIndex}][title]`;
        section.querySelector('.content-field').name = `sections[${currentIndex}][content]`;
        section.querySelector('.image-field').name = `sections[${currentIndex}][image]`;
        section.querySelector('.image-position-field').name = `sections[${currentIndex}][image_position]`;

        wrapper.appendChild(fragment);

        bindButtons(section);

        const newTextarea = section.querySelector(`textarea[name="sections[${currentIndex}][content]"]`);
        initEditor(newTextarea);

        index++;
    }


    wrapper.addEventListener(
        'click',
        function(e){
            const removeBtn = e.target.closest('.remove-section');
            if (removeBtn) {
                e.preventDefault();
                const item = removeBtn.closest('.section-item');
                if (item) {
                    item.remove();
                }
            }
        }
    );

    function initEditor(el)
    {
        if (el.dataset.joditInitialized) {
            return;
        }

        el.dataset.joditInitialized = true;

        Jodit.make(el, {
            height: 420,
            minHeight: 300,
            toolbarButtonSize: 'middle',
            theme: 'default',
            language: 'en',
            defaultMode: Jodit.MODE_WYSIWYG,

            cleanHTML: {
                fillEmptyParagraph: false
            },

            buttons: [
                'bold','italic','underline',
                'strikethrough','|',

                'ul','ol','|',

                'outdent','indent','|',

                'font','fontsize',
                'brush','paragraph','|',

                'image','link','|',

                'align','|',

                'hr','table','|',

                'undo','redo','|',

                'eraser',
                'copyformat','|',

                'source'
            ],

            uploader: {
                insertImageAsBase64URI: true
            },

            showCharsCounter: true,
            showWordsCounter: true,
            showXPathInStatusbar: false,
        });
    }

    function createButton(sectionIndex, buttonIndex)
    {
        return `
            <div class="button-item row g-2 border p-2 mb-2 mt-2">
                <div class="col-md-6">
                    <input
                        type="text"
                        name="sections[${sectionIndex}][buttons][${buttonIndex}][text]"
                        class="form-control"
                        placeholder="e.g. Learn more">
                </div>

                <div class="col-md-5">
                    <input
                        type="text"
                        name="sections[${sectionIndex}][buttons][${buttonIndex}][link]"
                        class="form-control"
                        placeholder="e.g. /contact or full URL">
                </div>

                <div class="col-md-1">
                    <button
                        type="button"
                        class="btn btn-outline-danger remove-button">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
    }

    // remove button script
    document.addEventListener(
        'click',
        function(e){
            if(e.target.classList.contains('remove-button')){
                e.target.closest(
                    '.button-item'
                ).remove();
            }
        }
    );

    function bindButtons(section)
    {
        if (section.dataset.pageSectionButtonsBound === '1') {
            return;
        }
        section.dataset.pageSectionButtonsBound = '1';

        let buttonsWrapper =
            section.querySelector(
                '.buttons-wrapper'
            );

        let sectionIndex =
            Array.from(
                wrapper.children
            ).indexOf(section);


        let buttonIndex =
            buttonsWrapper.querySelectorAll(
                '.button-item'
            ).length;


        section.querySelector(
            '.add-button'
        ).addEventListener(
            'click',
            function(){

                // limit 5 buttons
                if(buttonIndex >= 5){
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ icon: 'info', title: 'Button limit', text: 'Only 5 buttons are allowed per section.', confirmButtonText: 'OK', buttonsStyling: false, customClass: { confirmButton: 'admin-swal-btn-confirm', popup: 'admin-swal-popup' } });
                    } else {
                        alert('Only 5 buttons allowed');
                    }
                    return;
                }

                buttonsWrapper.insertAdjacentHTML(
                    'beforeend',

                    createButton(sectionIndex,buttonIndex)
                );

                buttonIndex++;
            }
        );
    }
</script>
@endpush