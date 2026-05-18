@php
    $rowPrefix = $namePrefix ?? 'links_1';
    $rowIndex = $index ?? 0;
    $rowLink = $link ?? null;
    $rowId = old($rowPrefix.'.'.$rowIndex.'.id', $rowLink?->id ?? '');
    $rowLabel = old($rowPrefix.'.'.$rowIndex.'.label', $rowLink?->label ?? '');
    $rowUrl = old($rowPrefix.'.'.$rowIndex.'.url', $rowLink?->url ?? '');
    $rowTarget = old($rowPrefix.'.'.$rowIndex.'.target', $rowLink?->target ?? '_self');
@endphp
<tr data-footer-link-row>
    <td class="text-center text-muted footer-drag-handle-cell">
        <button type="button" class="btn btn-sm btn-link text-muted p-0 border-0 footer-drag-handle" title="Drag to reorder" tabindex="-1" aria-label="Drag to reorder">
            <i class="fas fa-grip-vertical"></i>
        </button>
    </td>
    <td>
        <input type="hidden" name="{{ $rowPrefix }}[{{ $rowIndex }}][id]" value="{{ $rowId }}">
        <input type="text" name="{{ $rowPrefix }}[{{ $rowIndex }}][label]" class="form-control form-control-sm footer-link-label" value="{{ $rowLabel }}" maxlength="150" placeholder="e.g. Link label">
    </td>
    <td class="footer-link-url-cell">
        <select class="form-select form-select-sm footer-url-preset" aria-label="Page or section">
            <option value="">— Choose where this link goes —</option>
            @include('admin.partials.menu-link-preset-options')
        </select>
        <input type="text" name="{{ $rowPrefix }}[{{ $rowIndex }}][url]" class="form-control form-control-sm font-monospace footer-url-input mt-1" value="{{ $rowUrl }}" maxlength="500" placeholder="/about or https://…" autocomplete="off">
    </td>
    <td>
        <select name="{{ $rowPrefix }}[{{ $rowIndex }}][target]" class="form-select form-select-sm">
            <option value="_self" {{ $rowTarget === '_blank' ? '' : 'selected' }}>Same tab</option>
            <option value="_blank" {{ $rowTarget === '_blank' ? 'selected' : '' }}>New tab</option>
        </select>
    </td>
    <td class="text-end">
        <button type="button" class="btn btn-sm btn-outline-danger" data-remove-footer-link title="Remove row">&times;</button>
    </td>
</tr>
