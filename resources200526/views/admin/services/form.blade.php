@extends('layouts.admin')

@section('title', isset($service->id) ? 'Edit Service' : 'Add Service')

@section('content')

@php use App\Support\ServiceFormLimits; @endphp

<div class="page-header-bar">
    <h1>{{ isset($service->id) ? 'Edit Service' : 'Add Service' }}</h1>
    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($service->id) ? route('admin.services.update', $service) : route('admin.services.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($service->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label d-flex flex-wrap align-items-baseline justify-content-between gap-2" for="titleInput">
                            <span>Title *</span>
                            <span id="titleCharHint" class="text-muted small fw-normal"></span>
                        </label>
                        <input type="text" name="title" id="titleInput" class="form-control" value="{{ old('title', $service->title) }}" required maxlength="{{ ServiceFormLimits::TITLE_MAX }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="slugInput">Slug</label>
                        <input type="text" name="slug" id="slugInput" class="form-control" value="{{ old('slug', $service->slug) }}" placeholder="auto-generated if empty" maxlength="{{ ServiceFormLimits::SLUG_MAX }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex flex-wrap align-items-baseline justify-content-between gap-2" for="shortDescriptionInput">
                            <span>Short Description *</span>
                            <span id="shortDescCharHint" class="text-muted small fw-normal"></span>
                        </label>
                        <textarea name="short_description" id="shortDescriptionInput" class="form-control" rows="3" required maxlength="{{ ServiceFormLimits::SHORT_DESCRIPTION_MAX }}">{{ old('short_description', $service->short_description) }}</textarea>
                        <div class="form-text">Plain text for cards and listings (max {{ ServiceFormLimits::SHORT_DESCRIPTION_MAX }} characters).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Badge Label</label>
                        <input type="text" name="badge" class="form-control" value="{{ old('badge', $service->badge) }}" placeholder="e.g. SIGNATURE" maxlength="{{ ServiceFormLimits::BADGE_MAX }}">
                        <div class="form-text">Short label shown above the service title on the services listing page (e.g. SIGNATURE, STATEMENT).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Feature Highlights</label>
                        <div id="features-list">
                            @php $featureItems = old('features', $service->features ?? []); @endphp
                            @forelse($featureItems as $feat)
                                <div class="input-group mb-2 feature-row">
                                    <input type="text" name="features[]" class="form-control" value="{{ $feat }}" placeholder="e.g. Marble-like luxury finish" maxlength="{{ ServiceFormLimits::FEATURE_LINE_MAX }}">
                                    <button type="button" class="btn btn-outline-danger btn-remove-feature">×</button>
                                </div>
                            @empty
                                <div class="input-group mb-2 feature-row">
                                    <input type="text" name="features[]" class="form-control" placeholder="e.g. Marble-like luxury finish" maxlength="{{ ServiceFormLimits::FEATURE_LINE_MAX }}">
                                    <button type="button" class="btn btn-outline-danger btn-remove-feature">×</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="btn-add-feature" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-plus me-1"></i>Add Feature
                        </button>
                        <div class="form-text mt-1">Shown as checkmark bullet points on the services listing page.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex flex-wrap align-items-baseline justify-content-between gap-2" for="postContent">
                            <span>Full Description</span>
                            <span id="fullDescCharHint" class="text-muted small fw-normal"></span>
                        </label>
                        <textarea name="description" id="postContent" class="form-control wysiwyg" rows="6" maxlength="{{ ServiceFormLimits::DESCRIPTION_MAX }}">{{ old('description', $service->description) }}</textarea>
                        <div class="form-text">Including HTML, up to {{ number_format(ServiceFormLimits::DESCRIPTION_MAX) }} characters. Some editors only enforce this on save.</div>
                    </div>

                    @php
                        $relatedFinishIds = collect(old('finish_ids', isset($service->id) ? $service->finishes->pluck('id') : []))
                            ->map(fn ($id) => (int) $id)
                            ->filter(fn ($id) => $id > 0)
                            ->unique()
                            ->values()
                            ->all();
                        $relatedFinishesCatalog = ($finishes ?? collect())->map(fn ($f) => ['id' => (int) $f->id, 'title' => (string) $f->title])->values();
                    @endphp
                    <div class="mb-3 related-finishes-picker" id="related-finishes-root"
                         data-catalog='@json($relatedFinishesCatalog)'
                         data-selected='@json($relatedFinishIds)'>
                        <label class="form-label" for="related-finishes-add">Related finishes</label>
                        <div id="related-finishes-chips" class="d-flex flex-wrap gap-2 mb-2" role="list" aria-label="Selected finishes"></div>
                        <div id="related-finishes-inputs"></div>
                        <select id="related-finishes-add" class="form-select" autocomplete="off">
                            <option value="">Choose a finish to add…</option>
                        </select>
                        <div class="form-text">Add finishes from the list; click <span class="text-nowrap">×</span> on a tag to remove. Shown on the public service detail page.</div>
                    </div>

                    @include('admin.partials.seo-panel', [
                        'seo'            => $service->seoMeta ?? null,
                        'titleFieldId'   => 'titleInput',
                        'slugFieldId'    => 'slugInput',
                        'contentFieldId' => 'postContent',
                    ])
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Card image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                               @if(!isset($service->id) || !$service->image) required @endif>
                        <div class="form-text">Default image on the home page service card.</div>
                        @if(isset($service->id) && $service->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$service->image) }}" class="img-preview" style="height:80px;">
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label d-flex flex-wrap align-items-baseline justify-content-between gap-2" for="hoverTitleInput">
                            <span>Hover title</span>
                            <span class="text-muted small fw-normal">Optional</span>
                        </label>
                        <input type="text" name="hover_title" id="hoverTitleInput" class="form-control"
                               value="{{ old('hover_title', $service->hover_title) }}"
                               placeholder="Shown when hovering the card on the home page"
                               maxlength="{{ ServiceFormLimits::TITLE_MAX }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hover image <span class="text-danger">*</span></label>
                        <input type="file" name="hover_image" class="form-control"
                               accept=".jpg,.jpeg,.png,.gif,.webp,.svg,image/jpeg,image/png,image/gif,image/webp,image/svg+xml"
                               @if(!isset($service->id) || !$service->hover_image) required @endif>
                        <div class="form-text">Replaces the card image when visitors hover on the home page.</div>
                        @if(isset($service->id) && $service->hover_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$service->hover_image) }}" class="img-preview" style="height:80px;" alt="">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_hover_image" value="1" id="removeHoverImage">
                                    <label class="form-check-label" for="removeHoverImage">Remove hover image</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon) }}" placeholder="e.g. fas fa-paint-roller" maxlength="{{ ServiceFormLimits::ICON_MAX }}">
                        <div class="form-text">FontAwesome icon class e.g. <code>fas fa-code</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $service->exists ? $service->sort_order : ($defaultSortOrder ?? 1)) }}" min="1">
                        <div class="form-text">Must be unique among all services.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($service->id) ? 'Update Service' : 'Create Service' }}
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    function bindCounter(el, hintEl) {
        if (!el || !hintEl) return;
        function sync() {
            var max = el.maxLength > 0 ? el.maxLength : 0;
            var len = el.value.length;
            hintEl.textContent = max ? '(' + len + ' / ' + max + ')' : '(' + len + ')';
            hintEl.classList.toggle('text-danger', max > 0 && len >= max);
        }
        el.addEventListener('input', sync);
        sync();
    }
    bindCounter(document.getElementById('titleInput'), document.getElementById('titleCharHint'));
    bindCounter(document.getElementById('shortDescriptionInput'), document.getElementById('shortDescCharHint'));
    bindCounter(document.getElementById('postContent'), document.getElementById('fullDescCharHint'));
})();
</script>
<style>
    .related-finishes-picker .admin-finish-chip {
        background: var(--bs-light, #f8f9fa);
        border: 1px solid var(--bs-border-color, #dee2e6);
        font-size: 0.875rem;
        font-weight: 500;
        max-width: 100%;
    }
    .related-finishes-picker .admin-finish-chip .related-finish-remove {
        color: var(--bs-secondary, #6c757d);
        text-decoration: none;
        flex-shrink: 0;
        line-height: 1;
        padding: 0.125rem;
        border: 0;
        background: transparent;
        border-radius: 0.25rem;
    }
    .related-finishes-picker .admin-finish-chip .related-finish-remove:hover {
        color: var(--bs-danger, #dc3545);
        background: rgba(220, 53, 69, 0.08);
    }
</style>
<script>
document.getElementById('btn-add-feature').addEventListener('click', function () {
    const list = document.getElementById('features-list');
    const row = document.createElement('div');
    row.className = 'input-group mb-2 feature-row';
    row.innerHTML = '<input type="text" name="features[]" class="form-control" placeholder="e.g. Marble-like luxury finish" maxlength="{{ ServiceFormLimits::FEATURE_LINE_MAX }}"><button type="button" class="btn btn-outline-danger btn-remove-feature">&times;</button>';
    list.appendChild(row);
    row.querySelector('input').focus();
});
document.getElementById('features-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-remove-feature')) {
        const rows = document.querySelectorAll('.feature-row');
        if (rows.length > 1) {
            e.target.closest('.feature-row').remove();
        } else {
            e.target.closest('.feature-row').querySelector('input').value = '';
        }
    }
});

(function () {
    const root = document.getElementById('related-finishes-root');
    if (!root) return;

    let catalog = [];
    let selected = [];
    try {
        catalog = JSON.parse(root.dataset.catalog || '[]');
    } catch (e) {
        catalog = [];
    }
    try {
        selected = JSON.parse(root.dataset.selected || '[]').map(function (id) {
            return parseInt(id, 10);
        }).filter(function (id) {
            return id > 0;
        });
    } catch (e) {
        selected = [];
    }

    const chipsEl = document.getElementById('related-finishes-chips');
    const inputsEl = document.getElementById('related-finishes-inputs');
    const addSel = document.getElementById('related-finishes-add');

    function findFinish(id) {
        for (let i = 0; i < catalog.length; i++) {
            if (parseInt(catalog[i].id, 10) === id) return catalog[i];
        }
        return null;
    }

    function titleForId(id) {
        const f = findFinish(id);
        return f ? f.title : 'Unavailable finish (#' + id + ')';
    }

    function escapeHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function refreshAddSelect() {
        const taken = new Set(selected);
        addSel.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Choose a finish to add…';
        addSel.appendChild(placeholder);

        catalog
            .filter(function (c) {
                return !taken.has(parseInt(c.id, 10));
            })
            .slice()
            .sort(function (a, b) {
                return String(a.title).localeCompare(String(b.title), undefined, { sensitivity: 'base' });
            })
            .forEach(function (c) {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.title;
                addSel.appendChild(opt);
            });
    }

    function syncHiddenInputs() {
        inputsEl.innerHTML = '';
        selected.forEach(function (id) {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'finish_ids[]';
            inp.value = id;
            inputsEl.appendChild(inp);
        });
    }

    function renderChips() {
        chipsEl.innerHTML = '';
        selected.forEach(function (id) {
            const title = titleForId(id);
            const chip = document.createElement('span');
            chip.className =
                'admin-finish-chip rounded-pill d-inline-flex align-items-center gap-1 py-2 ps-3 pe-2';
            chip.setAttribute('role', 'listitem');
            chip.dataset.finishId = String(id);

            const label = document.createElement('span');
            label.className = 'text-break';
            label.innerHTML = escapeHtml(title);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'related-finish-remove';
            btn.setAttribute('aria-label', 'Remove ' + title);
            btn.innerHTML = '<i class="fas fa-times" aria-hidden="true"></i>';

            chip.appendChild(label);
            chip.appendChild(btn);
            chipsEl.appendChild(chip);
        });
        syncHiddenInputs();
    }

    addSel.addEventListener('change', function () {
        const id = parseInt(this.value, 10);
        if (!id) return;
        if (selected.indexOf(id) === -1) selected.push(id);
        this.value = '';
        refreshAddSelect();
        renderChips();
    });

    chipsEl.addEventListener('click', function (e) {
        const btn = e.target.closest('.related-finish-remove');
        if (!btn) return;
        const chip = btn.closest('[data-finish-id]');
        if (!chip) return;
        const id = parseInt(chip.dataset.finishId, 10);
        selected = selected.filter(function (x) {
            return x !== id;
        });
        refreshAddSelect();
        renderChips();
    });

    refreshAddSelect();
    renderChips();
})();
</script>
@endpush

@endsection
