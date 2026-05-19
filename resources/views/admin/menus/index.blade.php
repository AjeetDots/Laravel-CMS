@extends('layouts.admin')

@section('title', 'Menus')

@section('content')

<div class="page-header-bar">
    <h1>Navigation Menus</h1>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Menu Item
    </a>
</div>

<p class="text-muted small mb-4">Drag the <i class="fas fa-grip-vertical"></i> handle to change the main header menu order. Sub-items under a parent can be reordered within their group. Footer links: <a href="{{ route('admin.footer-navigation.edit') }}">Footer navigation</a>.</p>

@include('admin.partials.listing-toolbar', ['showStatus' => true])

<div class="card">
    <div class="card-body p-0">
        @if($menus->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bars fa-2x mb-2"></i>
                <p>No menu items match your filters. <a href="{{ route('admin.menus.index') }}">Clear filters</a> or <a href="{{ route('admin.menus.create') }}">add one</a></p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table mb-0 menu-admin-table" id="menu-admin-table">
                    <thead>
                        <tr>
                            <th style="width:3%" class="text-center" title="Drag to reorder"><i class="fas fa-grip-vertical text-muted opacity-50"></i></th>
                            <th>Label</th>
                            <th>URL</th>
                            <th>Target</th>
                            <th style="width:6%">Order</th>
                            <th>Status</th>
                            <th width="130">Actions</th>
                        </tr>
                    </thead>
                    @foreach($menus as $menu)
                    <tbody class="menu-sort-group" data-menu-group="{{ $menu->id }}">
                        <tr class="menu-row menu-row--top" data-menu-id="{{ $menu->id }}">
                            <td class="text-center menu-drag-handle-cell">
                                <button type="button" class="btn btn-sm btn-link text-muted p-0 border-0 menu-drag-handle" title="Drag to reorder" tabindex="-1" aria-label="Drag to reorder">
                                    <i class="fas fa-grip-vertical"></i>
                                </button>
                            </td>
                            <td class="fw-600">{{ $menu->label }}</td>
                            <td><code>{{ $menu->url }}</code></td>
                            <td>{{ $menu->target }}</td>
                            <td class="menu-sort-order-display">{{ $menu->sort_order }}</td>
                            <td>
                                <span class="badge {{ $menu->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                    {{ $menu->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.menus.edit', $menu) }}"
                                       class="btn btn-sm btn-icon btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline"
                                          data-delete-confirm="This menu item and its nested links will be removed from the navigation.">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-icon btn-outline-danger"
                                                data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @foreach($menu->allChildren as $child)
                        <tr class="menu-row menu-row--child" data-menu-id="{{ $child->id }}" style="background:#f8fafc;">
                            <td class="text-center menu-drag-handle-cell ps-4">
                                <button type="button" class="btn btn-sm btn-link text-muted p-0 border-0 menu-drag-handle menu-drag-handle--child" title="Drag to reorder submenu" tabindex="-1" aria-label="Drag to reorder submenu">
                                    <i class="fas fa-grip-vertical fa-sm"></i>
                                </button>
                            </td>
                            <td class="ps-2" style="color:#64748b;">
                                <i class="fas fa-level-down-alt fa-xs me-2"></i>{{ $child->label }}
                            </td>
                            <td><code>{{ $child->url }}</code></td>
                            <td>{{ $child->target }}</td>
                            <td class="menu-sort-order-display">{{ $child->sort_order }}</td>
                            <td>
                                <span class="badge {{ $child->is_active ? 'badge-active' : 'badge-inactive' }} px-2 py-1">
                                    {{ $child->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="{{ route('admin.menus.edit', $child) }}"
                                       class="btn btn-sm btn-icon btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></a>
                                    <form action="{{ route('admin.menus.destroy', $child) }}" method="POST" class="d-inline"
                                          data-delete-confirm="This menu link will be removed from the navigation.">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-icon btn-outline-danger"
                                                data-bs-toggle="tooltip" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endforeach
                </table>
            </div>
            <p class="text-muted small px-3 py-2 mb-0 border-top"><i class="fas fa-info-circle me-1"></i>Order saves automatically when you drop a row.</p>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<style>
.menu-admin-table .menu-drag-handle { cursor: grab; }
.menu-admin-table .sortable-ghost { opacity: 0.45; background: #f8fafc; }
.menu-admin-table .sortable-drag .menu-drag-handle { cursor: grabbing; }
.menu-admin-table tbody.menu-sort-group + tbody.menu-sort-group tr:first-child td { border-top-width: 0; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var table = document.getElementById('menu-admin-table');
    if (!table || typeof Sortable === 'undefined') return;

    var reorderUrl = @json(route('admin.menus.reorder'));
    var csrf = @json(csrf_token());
    var saveTimer = null;
    var saving = false;

    function refreshOrderDisplay() {
        var topOrder = 0;
        table.querySelectorAll('tbody.menu-sort-group').forEach(function (tbody) {
            topOrder++;
            var topRow = tbody.querySelector('.menu-row--top');
            if (topRow) {
                var cell = topRow.querySelector('.menu-sort-order-display');
                if (cell) cell.textContent = String(topOrder);
            }
            var childOrder = 0;
            tbody.querySelectorAll('.menu-row--child').forEach(function (row) {
                childOrder++;
                var childCell = row.querySelector('.menu-sort-order-display');
                if (childCell) childCell.textContent = String(childOrder);
            });
        });
    }

    function collectPayload() {
        var topLevel = [];
        var children = {};
        table.querySelectorAll('tbody.menu-sort-group').forEach(function (tbody) {
            var topRow = tbody.querySelector('.menu-row--top');
            if (!topRow) return;
            var parentId = topRow.getAttribute('data-menu-id');
            topLevel.push(parseInt(parentId, 10));
            var childIds = [];
            tbody.querySelectorAll('.menu-row--child').forEach(function (row) {
                childIds.push(parseInt(row.getAttribute('data-menu-id'), 10));
            });
            if (childIds.length) {
                children[parentId] = childIds;
            }
        });
        return { top_level: topLevel, children: children };
    }

    function saveOrder() {
        if (saving) return;
        saving = true;
        refreshOrderDisplay();
        fetch(reorderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(collectPayload()),
        })
            .then(function (res) {
                if (!res.ok) {
                    return res.json().then(function (data) {
                        var msg = data.message;
                        if (!msg && data.errors) {
                            msg = Object.values(data.errors).flat().join(' ');
                        }
                        throw new Error(msg || 'Could not save menu order.');
                    });
                }
                return res.json();
            })
            .then(function () {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Menu order saved',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                }
            })
            .catch(function (err) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Save failed', text: err.message || 'Please refresh and try again.' });
                } else {
                    alert(err.message || 'Could not save menu order.');
                }
            })
            .finally(function () {
                saving = false;
            });
    }

    function scheduleSave() {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveOrder, 300);
    }

    Sortable.create(table, {
        draggable: 'tbody.menu-sort-group',
        handle: '.menu-row--top .menu-drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        dragClass: 'sortable-drag',
        onEnd: scheduleSave,
    });

    table.querySelectorAll('tbody.menu-sort-group').forEach(function (tbody) {
        var childRows = tbody.querySelectorAll('.menu-row--child');
        if (!childRows.length) return;
        Sortable.create(tbody, {
            draggable: '.menu-row--child',
            handle: '.menu-drag-handle--child',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: scheduleSave,
        });
    });
});
</script>
@endpush
