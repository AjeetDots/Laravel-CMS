@php
    $showStatus = $showStatus ?? true;
    $showRead = $showRead ?? false;
    $categoryOptions = $categoryOptions ?? null;
    $galleryCategoryOptions = $galleryCategoryOptions ?? null;
    $showProjectType = $showProjectType ?? false;
    $hiddenFields = $hiddenFields ?? [];
@endphp
<form method="get" action="{{ url()->current() }}" class="row g-2 align-items-end mb-3 flex-wrap">
    @foreach($hiddenFields as $hfName => $hfValue)
        <input type="hidden" name="{{ $hfName }}" value="{{ $hfValue }}">
    @endforeach
    <div class="col-12 col-md-4 col-lg-3">
        <label class="form-label small text-secondary mb-0">Search</label>
        <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm"
               placeholder="Search…" autocomplete="off">
    </div>
    @if($showStatus)
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
            <label class="form-label small text-secondary mb-0">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="1" @selected((string) request('status') === '1')>Active</option>
                <option value="0" @selected((string) request('status') === '0')>Inactive</option>
            </select>
        </div>
    @endif
    @if($showRead)
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
            <label class="form-label small text-secondary mb-0">Read</label>
            <select name="read" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="0" @selected((string) request('read') === '0')>Unread</option>
                <option value="1" @selected((string) request('read') === '1')>Read</option>
            </select>
        </div>
    @endif
    @if($categoryOptions !== null && count($categoryOptions))
        <div class="col-12 col-md-4 col-lg-3">
            <label class="form-label small text-secondary mb-0">Category</label>
            <select name="category_id" class="form-select form-select-sm">
                <option value="">All categories</option>
                @foreach($categoryOptions as $cid => $cname)
                    <option value="{{ $cid }}" @selected((string) request('category_id') === (string) $cid)>{{ $cname }}</option>
                @endforeach
            </select>
        </div>
    @endif
    @if($showProjectType)
        <div class="col-12 col-sm-6 col-md-3 col-lg-2">
            <label class="form-label small text-secondary mb-0">Project type</label>
            <select name="project_type" class="form-select form-select-sm">
                <option value="">All types</option>
                <option value="reference" @selected(request('project_type') === 'reference')>Reference</option>
                <option value="real" @selected(request('project_type') === 'real')>Real</option>
            </select>
        </div>
    @endif
    @if($galleryCategoryOptions !== null && count($galleryCategoryOptions))
        <div class="col-12 col-md-4 col-lg-3">
            <label class="form-label small text-secondary mb-0">Gallery category</label>
            <select name="gallery_category_id" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="none" @selected(request('gallery_category_id') === 'none')>Uncategorised</option>
                @foreach($galleryCategoryOptions as $gcid => $gcname)
                    <option value="{{ $gcid }}" @selected((string) request('gallery_category_id') === (string) $gcid)>{{ $gcname }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="col-12 col-md-auto d-flex gap-2 align-items-end">
        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
        <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>
