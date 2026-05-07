@extends('layouts.admin')

@section('title', isset($page->id) ? 'Edit Page' : 'Create Page')

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
                <div class="card-header">Page Content</div>
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
                    <div class="mb-3">
                        <!-- <label class="form-label">Content</label> -->
                        <!-- <textarea name="content" id="postContent" class="form-control wysiwyg" rows="12">{{ old('content', $page->content) }}</textarea> -->
                        <!-- <div class="form-text">You can use HTML tags for formatting.</div> -->
                        <div class="card">
                            <div class="card-header">

                                Sections

                                <button type="button"
                                        id="addSection"
                                        class="btn btn-sm btn-primary float-end">

                                    Add Section
                                </button>

                            </div>

                            <div class="card-body">

                                <div id="sectionsWrapper">
                                    @foreach($page->sections ?? [] as $index => $section)

                                        @php
                                            $data = $section->data;
                                        @endphp

                                        @include('admin.pages.partials.section',
                                            [
                                                'index' => $index,
                                                'data' => $data
                                            ]
                                        )
                                    @endforeach
                                </div>
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
                        <label class="form-label">Template</label>
                        <select name="template" class="form-select">
                            @foreach($templates as $value => $label)
                                <option value="{{ $value }}" {{ old('template', $page->template ?? 'default') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Published</label>
                    </div>
                </div>
            </div>
            {{-- Legacy fields kept for backwards compatibility --}}
            <input type="hidden" name="meta_title"       value="{{ old('seo.meta_title',       $page->seoMeta?->meta_title       ?? $page->meta_title) }}">
            <input type="hidden" name="meta_description" value="{{ old('seo.meta_description', $page->seoMeta?->meta_description ?? $page->meta_description) }}">
        </div>
    </div>

    <div class="d-flex gap-2 mt-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>{{ isset($page->id) ? 'Update Page' : 'Create Page' }}
        </button>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<template id="sectionTemplate">

    <div class="section-item border p-3 mb-3">
        <h5>Section</h5>
        <input type="hidden" class="position-field">

        <div class="mb-3">
            <label>Type</label>
            <select class="form-control type-field">
                <option value="media_content">Media Content</option>
            </select>
        </div>

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

        <button type="button" class="btn btn-danger remove-section">Remove</button>
    </div>

</template>

@endsection
@push('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.wysiwyg').forEach(function(el){
            initEditor(el);
        });
        document.querySelectorAll('.section-item').forEach(function(section){
            bindButtons(section);
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
        let currentIndex = index;
        let html =
            document.getElementById(
                'sectionTemplate'
            ).content.cloneNode(true);


        html.querySelector(
            '.type-field'
        ).name =
            `sections[${currentIndex}][type]`;


        html.querySelector(
            '.title-field'
        ).name =
            `sections[${currentIndex}][title]`;


        html.querySelector(
            '.content-field'
        ).name =
            `sections[${currentIndex}][content]`;


        html.querySelector(
            '.image-field'
        ).name =
            `sections[${currentIndex}][image]`;


        html.querySelector(
            '.image-position-field'
        ).name =
            `sections[${currentIndex}][image_position]`;


        wrapper.appendChild(html);

        // add button script
        // let section = wrapper.lastElementChild;
        // let buttonsWrapper = section.querySelector('.buttons-wrapper');
        // let buttonIndex = 0;

        // section.querySelector('.add-button').addEventListener(
        //     'click',
        //     function(){
        //         buttonsWrapper.insertAdjacentHTML(
        //             'beforeend',
        //             createButton(currentIndex, buttonIndex)
        //         );
        //         buttonIndex++;
        //     }
        // );

        let section = wrapper.lastElementChild;
        bindButtons(section);

        let newTextarea = wrapper.querySelector(`textarea[name="sections[${index}][content]"]`);
        initEditor(newTextarea);

        index++;
    }


    wrapper.addEventListener(
        'click',
        function(e){

            if(
                e.target.classList.contains(
                    'remove-section'
                )
            ){
                e.target.closest(
                    '.section-item'
                ).remove();
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
                        placeholder="Button Text">
                </div>

                <div class="col-md-5">
                    <input
                        type="text"
                        name="sections[${sectionIndex}][buttons][${buttonIndex}][link]"
                        class="form-control"
                        placeholder="Button Link">
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
                    alert('Only 5 buttons allowed');
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