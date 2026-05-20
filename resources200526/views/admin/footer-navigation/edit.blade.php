@extends('layouts.admin')

@section('title', 'Footer navigation')

@section('content')
<div class="page-header-bar">
    <div>
        <h1>Footer navigation</h1>
        <p class="text-muted mb-0 small">There are <strong>two</strong> footer columns (left and right). Name each column, then add the links underneath — like WordPress menus assigned to “Footer 1” and “Footer 2”. Header links stay under <a href="{{ route('admin.menus.index') }}">Menus</a>.</p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">View site</a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.footer-navigation.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header fw-600 d-flex align-items-center gap-2">
                    <span class="badge text-bg-primary">Column 1</span>
                    <span class="text-muted small fw-normal">First block in the footer row</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="slot_1_title">Column title</label>
                        <input type="text" name="slot_1_title" id="slot_1_title" class="form-control" required maxlength="120"
                               value="{{ old('slot_1_title', $col1->title) }}" placeholder="e.g. Explore">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="form-label mb-0">Links</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-add-footer-link="1">+ Add link</button>
                    </div>
                    <div class="table-responsive border rounded">
                        <table class="table table-sm align-middle mb-0 footer-links-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:4%" class="text-center" title="Drag to reorder"><i class="fas fa-grip-vertical text-muted opacity-50"></i></th>
                                    <th style="width:24%">Label</th>
                                    <th style="width:40%">Page / URL</th>
                                    <th style="width:18%">Target</th>
                                    <th style="width:6%"></th>
                                </tr>
                            </thead>
                            <tbody id="footer-links-tbody-1" data-footer-links-sortable>
                                @foreach($links1 as $i => $link)
                                    @include('admin.footer-navigation._link-row', [
                                        'namePrefix' => 'links_1',
                                        'index' => $i,
                                        'link' => $link,
                                    ])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="form-text mb-0 mt-2">Choose a page from the list or pick Custom URL. Drag rows to set order, then save.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header fw-600 d-flex align-items-center gap-2">
                    <span class="badge text-bg-primary">Column 2</span>
                    <span class="text-muted small fw-normal">Second block in the footer row</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="slot_2_title">Column title</label>
                        <input type="text" name="slot_2_title" id="slot_2_title" class="form-control" required maxlength="120"
                               value="{{ old('slot_2_title', $col2->title) }}" placeholder="e.g. Company">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="form-label mb-0">Links</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-add-footer-link="2">+ Add link</button>
                    </div>
                    <div class="table-responsive border rounded">
                        <table class="table table-sm align-middle mb-0 footer-links-table">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:4%" class="text-center" title="Drag to reorder"><i class="fas fa-grip-vertical text-muted opacity-50"></i></th>
                                    <th style="width:24%">Label</th>
                                    <th style="width:40%">Page / URL</th>
                                    <th style="width:18%">Target</th>
                                    <th style="width:6%"></th>
                                </tr>
                            </thead>
                            <tbody id="footer-links-tbody-2" data-footer-links-sortable>
                                @foreach($links2 as $i => $link)
                                    @include('admin.footer-navigation._link-row', [
                                        'namePrefix' => 'links_2',
                                        'index' => $i,
                                        'link' => $link,
                                    ])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="form-text mb-0 mt-2">Same as column 1 — only these two columns appear in the footer.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save me-2"></i>Save footer navigation
        </button>
    </div>
</form>

<template id="footer-link-row-template">
    @include('admin.footer-navigation._link-row', [
        'namePrefix' => '__NAME_PREFIX__',
        'index' => '__I__',
        'link' => null,
    ])
</template>
@endsection

@push('styles')
<style>
.footer-links-table .footer-drag-handle { cursor: grab; }
.footer-links-table .sortable-ghost { opacity: 0.45; background: #f8fafc; }
.footer-links-table .sortable-drag .footer-drag-handle { cursor: grabbing; }
.footer-links-table .footer-url-input[readonly] { background-color: var(--bs-light, #f8f9fa); }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tpl = document.getElementById('footer-link-row-template');
    if (!tpl) return;

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

    function initFooterUrlRow(tr) {
        if (!tr || tr.dataset.footerUrlInit === '1') return;
        var preset = tr.querySelector('.footer-url-preset');
        var input = tr.querySelector('.footer-url-input');
        var labelInput = tr.querySelector('.footer-link-label');
        if (!preset || !input) return;
        tr.dataset.footerUrlInit = '1';

        function applyLock(isPreset) {
            if (isPreset) {
                input.readOnly = true;
                input.classList.add('bg-light');
            } else {
                input.readOnly = false;
                input.classList.remove('bg-light');
            }
        }

        preset.addEventListener('change', function () {
            var val = preset.value;
            if (val === '' || val === '__custom__') {
                applyLock(false);
                return;
            }
            input.value = val;
            applyLock(true);
            if (labelInput && labelInput.value.trim() === '') {
                var opt = preset.options[preset.selectedIndex];
                if (opt && opt.text) {
                    labelInput.value = opt.text.replace(/\s*\(draft\)\s*$/i, '').trim();
                }
            }
        });

        function syncPresetFromInput() {
            if (isExternalUrl(input.value)) {
                preset.value = '__custom__';
                applyLock(false);
                return;
            }
            var needle = menuPathForMatch(input.value);
            if (needle === null) {
                preset.value = '__custom__';
                applyLock(false);
                return;
            }
            if (needle === '' && input.value.trim() === '') {
                preset.value = '';
                applyLock(false);
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
            applyLock(found);
        }

        input.addEventListener('input', syncPresetFromInput);
        input.addEventListener('change', syncPresetFromInput);
        syncPresetFromInput();
    }

    function slotFromTbody(tbody) {
        var m = tbody.id.match(/footer-links-tbody-(\d+)/);
        return m ? m[1] : '';
    }

    function renumberRows(tbody) {
        var slot = slotFromTbody(tbody);
        if (!slot) return;
        var prefix = 'links_' + slot;
        tbody.querySelectorAll('tr[data-footer-link-row]').forEach(function (tr, i) {
            tr.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace(/^links_\d+\[\d+\]/, prefix + '[' + i + ']');
            });
        });
    }

    document.querySelectorAll('[data-footer-links-sortable]').forEach(function (tbody) {
        if (typeof Sortable === 'undefined') return;
        Sortable.create(tbody, {
            handle: '.footer-drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function () {
                renumberRows(tbody);
            },
        });
        tbody.querySelectorAll('tr[data-footer-link-row]').forEach(initFooterUrlRow);
    });

    document.querySelectorAll('[data-add-footer-link]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slot = btn.getAttribute('data-add-footer-link');
            var tbody = document.getElementById('footer-links-tbody-' + slot);
            if (!tbody || !tpl) return;
            var i = tbody.querySelectorAll('tr[data-footer-link-row]').length;
            var prefix = 'links_' + slot;
            var html = tpl.innerHTML
                .replace(/__NAME_PREFIX__/g, prefix)
                .replace(/__I__/g, String(i));
            var wrap = document.createElement('tbody');
            wrap.innerHTML = html.trim();
            var tr = wrap.firstElementChild;
            if (!tr) return;
            tbody.appendChild(tr);
            initFooterUrlRow(tr);
            renumberRows(tbody);
        });
    });

    document.addEventListener('click', function (e) {
        var rm = e.target && e.target.closest ? e.target.closest('[data-remove-footer-link]') : null;
        if (!rm) return;
        var tr = rm.closest('tr[data-footer-link-row]');
        var tbody = tr && tr.parentElement;
        if (!tr || !tbody || !tbody.id || !tbody.id.startsWith('footer-links-tbody-')) return;
        tr.remove();
        renumberRows(tbody);
    });
});
</script>
@endpush
