@extends('layouts.admin')

@section('title', isset($menu->id) ? 'Edit Menu Item' : 'Add Menu Item')

@section('content')

<div class="page-header-bar">
    <h1>{{ isset($menu->id) ? 'Edit Menu Item' : 'Add Menu Item' }}</h1>
    <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($menu->id) ? route('admin.menus.update', $menu) : route('admin.menus.store') }}"
              method="POST">
            @csrf
            @if(isset($menu->id)) @method('PUT') @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            @php
                $__mlm = old('menu_link_mode');
                if ($__mlm !== 'preset' && $__mlm !== 'custom') {
                    $__u = (string) old('url', $menu->url ?? '');
                    $__mlm = ($__u !== '' && \App\Support\MenuLinkDirectory::isPresetPath($__u)) ? 'preset' : 'custom';
                }
            @endphp
            <input type="hidden" name="menu_link_mode" id="menuLinkMode" value="{{ $__mlm }}">

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Label (text in the menu) *</label>
                        <input type="text" name="label" class="form-control" value="{{ old('label', $menu->label) }}" required placeholder="e.g. Home">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="menuUrlPreset">Page or section</label>
                        <select id="menuUrlPreset" class="form-select">
                            <option value="">— Choose where this link goes —</option>
                            @include('admin.partials.menu-link-preset-options')
                        </select>
                        <div class="form-text">Pick a destination here; the URL is filled for you and locked unless you choose Custom URL.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="menuUrlInput">URL</label>
                        <input type="text" name="url" id="menuUrlInput" class="form-control" value="{{ old('url', $menu->url) }}" placeholder="/about or https://…" autocomplete="off">
                        <div class="form-text" id="menuUrlHelp">Choose Custom URL above to edit the address (external sites or paths not in the list).</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Parent Menu</label>
                        <select name="parent_id" class="form-select">
                            <option value="">— Top Level —</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $menu->exists ? $menu->parent_id : request('parent_id')) == $parent->id ? 'selected' : '' }}>{{ $parent->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Target</label>
                        <select name="target" class="form-select">
                            <option value="_self" {{ old('target', $menu->target ?? '_self') == '_self' ? 'selected' : '' }}>Same Window (_self)</option>
                            <option value="_blank" {{ old('target', $menu->target) == '_blank' ? 'selected' : '' }}>New Tab (_blank)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $menu->exists ? $menu->sort_order : ($defaultSortOrder ?? 0)) }}" min="0">
                        <div class="form-text">Must be unique for other links at the same level (top level, or under the same parent).</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                               {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ isset($menu->id) ? 'Update' : 'Create' }} Menu Item
                </button>
                <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var preset = document.getElementById('menuUrlPreset');
    var input = document.getElementById('menuUrlInput');
    var modeInput = document.getElementById('menuLinkMode');
    var helpEl = document.getElementById('menuUrlHelp');
    if (!preset || !input || !modeInput) return;

    function menuPathForMatch(raw) {
        raw = (raw || '').trim();
        if (!raw || raw === '#') return '';
        if (/^https?:\/\//i.test(raw)) {
            try {
                var u = new URL(raw);
                if (u.origin !== window.location.origin) return null;
                raw = u.pathname || '/';
            } catch (e) {
                return null;
            }
        }
        if (raw === '/') return '/';
        return '/' + raw.replace(/^\/+|\/+$/g, '');
    }

    function isExternalUrl(raw) {
        raw = (raw || '').trim();
        if (!/^https?:\/\//i.test(raw)) return false;
        try {
            return new URL(raw).origin !== window.location.origin;
        } catch (e) {
            return true;
        }
    }

    function applyLockFromMode() {
        if (modeInput.value === 'preset') {
            input.readOnly = true;
            input.classList.add('bg-light');
            if (helpEl) {
                helpEl.textContent = 'This address is set from your choice above. Pick Custom URL to change it.';
            }
        } else {
            input.readOnly = false;
            input.classList.remove('bg-light');
            if (helpEl) {
                helpEl.textContent = 'You can type any path or a full https:// link. Pick a page above to lock again.';
            }
        }
    }

    preset.addEventListener('change', function () {
        var val = preset.value;
        if (val === '' || val === '__custom__') {
            modeInput.value = 'custom';
            applyLockFromMode();
            return;
        }
        input.value = val;
        modeInput.value = 'preset';
        applyLockFromMode();
    });

    function syncPresetFromInput() {
        if (isExternalUrl(input.value)) {
            preset.value = '__custom__';
            modeInput.value = 'custom';
            applyLockFromMode();
            return;
        }
        var needle = menuPathForMatch(input.value);
        if (needle === null) {
            preset.value = '__custom__';
            modeInput.value = 'custom';
            applyLockFromMode();
            return;
        }
        if (needle === '' && input.value.trim() === '') {
            preset.value = '';
            modeInput.value = 'custom';
            applyLockFromMode();
            return;
        }
        var found = false;
        for (var i = 0; i < preset.options.length; i++) {
            var opt = preset.options[i];
            if (!opt.value || opt.value === '__custom__') continue;
            if (menuPathForMatch(opt.value) === needle) {
                preset.value = opt.value;
                found = true;
                break;
            }
        }
        if (!found) {
            preset.value = input.value.trim() ? '__custom__' : '';
        }
        modeInput.value = found ? 'preset' : 'custom';
        applyLockFromMode();
    }

    input.addEventListener('input', syncPresetFromInput);
    input.addEventListener('change', syncPresetFromInput);
    syncPresetFromInput();
});
</script>
@endpush
