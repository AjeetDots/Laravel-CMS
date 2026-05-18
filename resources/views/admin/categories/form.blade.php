@extends('layouts.admin')
@section('title', $category->id ? 'Edit Category' : 'Add Category')

@section('styles')
<style>
    .category-page-header { margin-bottom: 1.25rem; }
    .category-page-header .page-header-bar { align-items: flex-start; }
    .category-page-header h1.category-page-title {
        font-size: clamp(1.45rem, 2.4vw, 1.95rem);
        font-weight: 700;
        letter-spacing: -0.02em;
        color: var(--dark, #1c1714);
        line-height: 1.15;
        margin: 0;
        flex: 1 1 auto;
        min-width: 0;
    }
    .category-page-header .category-page-lead { max-width: 48rem; line-height: 1.45; }
    .category-form-premium .form-label { font-weight: 600; color: #3d3630; letter-spacing: .01em; }
    .category-form-premium .form-control,
    .category-form-premium .form-select { border-radius: 8px; border-color: #e4dcd0; }
    .category-form-premium .form-control:focus,
    .category-form-premium .form-select:focus { border-color: #c9a063; box-shadow: 0 0 0 0.2rem rgba(201, 160, 99, 0.18); }
    .category-form-premium .section-eyebrow {
        font-size: .65rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase;
        color: #9a8f82; margin-bottom: .35rem;
    }
    .category-form-premium .section-title { font-size: .95rem; font-weight: 600; color: #2a2622; margin-bottom: 1rem; }
    .category-form-premium .sort-order-panel {
        background: linear-gradient(165deg, #fdfcfa 0%, #f7f2ea 100%);
        border: 1px solid rgba(200, 162, 90, 0.28);
        border-radius: 12px;
        padding: 1.25rem 1.35rem;
    }
    .category-form-premium .active-panel {
        background: #fffefb;
        border: 1px solid rgba(42, 38, 34, 0.08);
        border-radius: 12px;
        padding: 1.25rem 1.35rem;
        height: 100%;
    }
    .category-form-premium .form-check-input:checked { background-color: #8b6914; border-color: #8b6914; }
</style>
@endsection

@section('content')

<div class="category-page-header">
    <div class="page-header-bar d-flex flex-wrap justify-content-between align-items-start gap-3">
        <h1 class="category-page-title">{{ $category->id ? 'Edit Category' : 'Add Category' }}</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary flex-shrink-0">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>
    <p class="text-slate-admin category-page-lead mb-0 mt-2 small">Organise blog posts under clear labels. Name and slug define the URL; parent sets hierarchy where your theme supports it.</p>
</div>

<div class="w-100">
    <div class="card category-form-premium">
        <div class="card-header py-3">
            <div class="text-uppercase small text-secondary opacity-75" style="letter-spacing:.14em;font-size:.68rem;">Blog</div>
            <p class="small text-muted mb-0 mt-2">Complete the fields below, then save. Slug can stay blank to auto-generate from the name.</p>
        </div>
        <div class="card-body p-4 p-lg-5">
                <form action="{{ $category->id ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                      method="POST">
                    @csrf
                    @if($category->id) @method('PUT') @endif

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="mb-4 pb-1">
                        <div class="section-eyebrow">Basics</div>
                        <div class="section-title">Identity</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="catName">Name *</label>
                                <input type="text" name="name" id="catName" class="form-control"
                                       value="{{ old('name', $category->name) }}" required autofocus
                                       placeholder="e.g. Project stories">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="catSlug">Slug</label>
                                <input type="text" name="slug" id="catSlug" class="form-control"
                                       value="{{ old('slug', $category->slug) }}" placeholder="auto-generated if empty">
                                <div class="form-text">Used in URLs; leave blank to derive from the name.</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-4 pb-1">
                        <div class="section-eyebrow">Structure</div>
                        <div class="section-title">Hierarchy &amp; copy</div>
                        <div class="mb-3">
                            <label class="form-label" for="catParent">Parent category</label>
                            <select name="parent_id" id="catParent" class="form-select">
                                <option value="">— None (top level) —</option>
                                @foreach($parents as $pid => $pname)
                                    <option value="{{ $pid }}"
                                        {{ (string) old('parent_id', $category->exists ? $category->parent_id : request('parent_id')) === (string) $pid ? 'selected' : '' }}>
                                        {{ $pname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="catDescription">Description</label>
                            <textarea name="description" id="catDescription" class="form-control" rows="4"
                                      placeholder="Optional — shown where your theme displays category intros.">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="mb-0">
                        <div class="section-eyebrow">Publishing</div>
                        <div class="section-title">Order &amp; visibility</div>
                        <div class="row g-3 align-items-stretch">
                            <div class="col-md-7">
                                <div class="sort-order-panel h-100">
                                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-2">
                                        <label class="form-label mb-0" for="catSortOrder">Sort order</label>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary border-0 text-primary px-2"
                                                data-bs-toggle="modal" data-bs-target="#categorySortGuideModal"
                                                title="How sort order works">
                                            <i class="fas fa-circle-info" aria-hidden="true"></i>
                                            <span class="visually-hidden">Open guide</span>
                                        </button>
                                    </div>
                                    <input type="number" name="sort_order" id="catSortOrder" class="form-control" min="1"
                                           style="max-width: 11rem;"
                                           value="{{ old('sort_order', $category->exists ? $category->sort_order : ($defaultSortOrder ?? 1)) }}">
                                    <div class="form-text mt-2 mb-0">Lower numbers typically appear first in category lists. Must be unique among siblings (same parent).</div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="active-panel d-flex flex-column justify-content-center">
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                                               role="switch"
                                               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="isActive">Active</label>
                                    </div>
                                    <p class="small text-muted mt-2 mb-0">Turn off to hide this category from public menus and pickers while keeping existing post links intact.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="d-flex flex-wrap gap-2 pt-1">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>{{ $category->id ? 'Update Category' : 'Create Category' }}
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
        </div>
    </div>
</div>

<div class="modal fade" id="categorySortGuideModal" tabindex="-1" aria-labelledby="categorySortGuideModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h2 class="modal-title fs-5" id="categorySortGuideModalLabel">Sort order</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="mb-2">Each <strong>parent group</strong> (top-level categories, or children under the same parent) has its own numbering. Two top-level categories cannot share the same sort value; neither can two siblings under the same parent.</p>
                <p class="mb-0 text-muted small">Use gaps (0, 10, 20…) if you expect to insert categories later without renumbering everything.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('catName').addEventListener('input', function () {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    const slugField = document.getElementById('catSlug');
    if (!slugField.dataset.touched) { slugField.placeholder = slug; }
});
document.getElementById('catSlug').addEventListener('input', function () {
    this.dataset.touched = '1';
});
</script>
@endsection
