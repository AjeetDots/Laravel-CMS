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
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:28%">Label</th>
                                    <th style="width:44%">URL</th>
                                    <th style="width:20%">Target</th>
                                    <th style="width:8%"></th>
                                </tr>
                            </thead>
                            <tbody id="footer-links-tbody-1">
                                @foreach($links1 as $i => $link)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="links_1[{{ $i }}][id]" value="{{ $link->id }}">
                                            <input type="text" name="links_1[{{ $i }}][label]" class="form-control form-control-sm" value="{{ old('links_1.'.$i.'.label', $link->label) }}" maxlength="150" placeholder="e.g. Link label">
                                        </td>
                                        <td>
                                            <input type="text" name="links_1[{{ $i }}][url]" class="form-control form-control-sm font-monospace" value="{{ old('links_1.'.$i.'.url', $link->url) }}" maxlength="500" placeholder="/page or https://…">
                                        </td>
                                        <td>
                                            <select name="links_1[{{ $i }}][target]" class="form-select form-select-sm">
                                                <option value="_self" {{ old('links_1.'.$i.'.target', $link->target) === '_blank' ? '' : 'selected' }}>Same tab</option>
                                                <option value="_blank" {{ old('links_1.'.$i.'.target', $link->target) === '_blank' ? 'selected' : '' }}>New tab</option>
                                            </select>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-footer-link title="Remove row">&times;</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="form-text mb-0 mt-2">Use paths like <code>/contact</code> or full URLs. Leave a row blank (or remove it) before saving to delete it.</p>
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
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:28%">Label</th>
                                    <th style="width:44%">URL</th>
                                    <th style="width:20%">Target</th>
                                    <th style="width:8%"></th>
                                </tr>
                            </thead>
                            <tbody id="footer-links-tbody-2">
                                @foreach($links2 as $i => $link)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="links_2[{{ $i }}][id]" value="{{ $link->id }}">
                                            <input type="text" name="links_2[{{ $i }}][label]" class="form-control form-control-sm" value="{{ old('links_2.'.$i.'.label', $link->label) }}" maxlength="150" placeholder="e.g. Link label">
                                        </td>
                                        <td>
                                            <input type="text" name="links_2[{{ $i }}][url]" class="form-control form-control-sm font-monospace" value="{{ old('links_2.'.$i.'.url', $link->url) }}" maxlength="500" placeholder="/page or https://…">
                                        </td>
                                        <td>
                                            <select name="links_2[{{ $i }}][target]" class="form-select form-select-sm">
                                                <option value="_self" {{ old('links_2.'.$i.'.target', $link->target) === '_blank' ? '' : 'selected' }}>Same tab</option>
                                                <option value="_blank" {{ old('links_2.'.$i.'.target', $link->target) === '_blank' ? 'selected' : '' }}>New tab</option>
                                            </select>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-footer-link title="Remove row">&times;</button>
                                        </td>
                                    </tr>
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
    <tr>
        <td>
            <input type="hidden" name="__NAME_PREFIX__[__I__][id]" value="">
            <input type="text" name="__NAME_PREFIX__[__I__][label]" class="form-control form-control-sm" maxlength="150" placeholder="e.g. Link label">
        </td>
        <td>
            <input type="text" name="__NAME_PREFIX__[__I__][url]" class="form-control form-control-sm font-monospace" maxlength="500" placeholder="/page or https://…">
        </td>
        <td>
            <select name="__NAME_PREFIX__[__I__][target]" class="form-select form-select-sm">
                <option value="_self" selected>Same tab</option>
                <option value="_blank">New tab</option>
            </select>
        </td>
        <td class="text-end">
            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-footer-link title="Remove row">&times;</button>
        </td>
    </tr>
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tpl = document.getElementById('footer-link-row-template');
    if (!tpl) return;

    function slotFromTbody(tbody) {
        var m = tbody.id.match(/footer-links-tbody-(\d+)/);
        return m ? m[1] : '';
    }

    function renumberRows(tbody) {
        var slot = slotFromTbody(tbody);
        if (!slot) return;
        var prefix = 'links_' + slot;
        tbody.querySelectorAll('tr').forEach(function (tr, i) {
            tr.querySelectorAll('[name]').forEach(function (el) {
        el.name = el.name.replace(/^links_\d+\[\d+\]/, prefix + '[' + i + ']');
            });
        });
    }

    document.querySelectorAll('[data-add-footer-link]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slot = btn.getAttribute('data-add-footer-link');
            var tbody = document.getElementById('footer-links-tbody-' + slot);
            if (!tbody || !tpl) return;
            var i = tbody.querySelectorAll('tr').length;
            var prefix = 'links_' + slot;
            var html = tpl.innerHTML
                .replace(/__NAME_PREFIX__/g, prefix)
                .replace(/__I__/g, String(i));
            var wrap = document.createElement('tbody');
            wrap.innerHTML = html.trim();
            var tr = wrap.firstElementChild;
            if (tr) tbody.appendChild(tr);
        });
    });

    document.addEventListener('click', function (e) {
        var t = e.target;
        if (!t || !t.closest) return;
        var rm = t.closest('[data-remove-footer-link]');
        if (!rm) return;
        var tr = rm.closest('tr');
        var tbody = tr && tr.parentElement;
        if (!tr || !tbody || !tbody.id || !tbody.id.startsWith('footer-links-tbody-')) return;
        tr.remove();
        renumberRows(tbody);
    });
});
</script>
@endpush
