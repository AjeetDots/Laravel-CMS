<div class="section-item card mb-3 border shadow-sm">

    <div class="card-header p-0 bg-light border-bottom">
        <div class="d-flex align-items-stretch flex-nowrap">
            <button type="button"
                    class="btn btn-link flex-grow-1 text-start text-decoration-none py-2 px-3 rounded-0 section-collapse-toggle d-flex align-items-center gap-2"
                    data-bs-toggle="collapse"
                    data-bs-target="#sectionCollapse-{{ $index }}"
                    aria-expanded="true"
                    aria-controls="sectionCollapse-{{ $index }}">
                <i class="fas fa-chevron-down section-item-chevron text-muted small" aria-hidden="true"></i>
                <span class="fw-semibold text-dark section-item-index-label">Section {{ $index + 1 }}</span>
            </button>
            <button type="button"
                    class="btn btn-outline-danger remove-section rounded-0 border-0 border-start px-3"
                    title="Remove this section">
                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                <span class="visually-hidden">Remove section</span>
            </button>
        </div>
    </div>

    <div id="sectionCollapse-{{ $index }}" class="collapse show section-item-collapse">
        <div class="card-body">
            <input type="hidden" name="sections[{{ $index }}][type]" value="media_content">

            <div class="mb-3">
                <label>Title</label>
                <input type="text"
                    name="sections[{{ $index }}][title]"
                    class="form-control"
                    value="{{ $data['title'] ?? '' }}"
                >
            </div>

            <div class="mb-3">
                <label>Content</label>
                <textarea
                    name="sections[{{ $index }}][content]"
                    class="form-control wysiwyg"
                >{{ $data['content'] ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input
                    type="file"
                    name="sections[{{ $index }}][image]"
                    class="form-control"
                >
                <input
                    type="hidden"
                    name="sections[{{ $index }}][existing_image]"
                    value="{{ $data['image'] ?? '' }}"
                >
                @if(! empty($data['image']))
                    <img
                        src="{{ asset('storage/'.$data['image']) }}"
                        width="100"
                        class="mt-2"
                        alt=""
                    >
                @endif
            </div>

            <div class="mb-3">
                <label>Position</label>
                <select name="sections[{{ $index }}][image_position]" class="form-control">
                    <option value="left" {{ ($data['image_position'] ?? '') == 'left' ? 'selected' : '' }}>Left</option>
                    <option value="right" {{ ($data['image_position'] ?? '') == 'right' ? 'selected' : '' }}>Right</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Buttons</label>
                <div class="buttons-wrapper">
                    @foreach($data['buttons'] ?? [] as $btnIndex => $button)
                        <div class="button-item row g-2 border p-2 mb-2 mt-2">
                            <div class="col-md-6">
                                <input
                                    type="text"
                                    name="sections[{{ $index }}][buttons][{{ $btnIndex }}][text]"
                                    class="form-control mb-2"
                                    placeholder="e.g. Learn more"
                                    value="{{ $button['text'] ?? '' }}"
                                >
                            </div>
                            <div class="col-md-5">
                                <input
                                    type="text"
                                    name="sections[{{ $index }}][buttons][{{ $btnIndex }}][link]"
                                    class="form-control mb-2"
                                    placeholder="e.g. /page or URL"
                                    value="{{ $button['link'] ?? '' }}"
                                >
                            </div>
                            <div class="col-md-1">
                                <button
                                    type="button"
                                    class="btn btn-outline-danger remove-button">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button
                    type="button"
                    class="btn btn-sm btn-secondary add-button">
                    + Add Button
                </button>
            </div>
        </div>
    </div>
</div>
