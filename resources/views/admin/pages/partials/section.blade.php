<div class="section-item border p-3 mb-3">

    <h5>Section</h5>


    <div class="mb-3">
        <label>Type</label>

        <select name="sections[{{ $index }}][type]" class="form-control">
            <option value="media_content"
                {{
                    $data['type'] ??
                    'media_content'
                    ==
                    'media_content'

                    ? 'selected'
                    : ''
                }}
            >Media Content</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Title</label>
        <input type="text"
            name="sections[{{ $index }}][title]"
            class="form-control"
            value="{{
                $data['title']
                ?? ''
            }}"
        >
    </div>

    <div class="mb-3">
        <label>Content</label>
        <textarea
            name="sections[{{ $index }}][content]"

            class="form-control wysiwyg"
        >{{
            $data['content']
            ?? ''
        }}</textarea>
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
        @if(!empty($data['image']))
            <img
                src="{{ asset(
                    'storage/'
                    .$data['image']
                ) }}"

                width="100"
                class="mt-2"
            >
        @endif
    </div>

    <div class="mb-3">
        <label>Position</label>
        <select name="sections[{{ $index }}][image_position]" class="form-control">
            <option value="left"
                {{
                    (
                        $data[
                            'image_position'
                        ]
                        ?? ''
                    )
                    ==
                    'left'
                    ? 'selected'
                    : ''
                }}
            >Left</option>

            <option value="right"
                {{
                    (
                        $data[
                            'image_position'
                        ]
                        ?? ''
                    )
                    ==
                    'right'
                    ? 'selected'
                    : ''
                }}
            >
                Right
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Buttons</label>
        <div class="buttons-wrapper">

            @foreach(
                $data['buttons']
                ?? []
                as $btnIndex => $button
            )
                <div class="button-item row g-2 border p-2 mb-2 mt-2">
                    <div class="col-md-6">
                        <input
                            type="text"
                            name="sections[{{ $index }}][buttons][{{ $btnIndex }}][text]"
                            class="form-control mb-2"
                            placeholder="Button Text"
                            value="{{
                                $button['text']
                                ?? '' 
                            }}"
                        >
                    </div>
                    <div class="col-md-5">
                        <input
                            type="text"
                            name="sections[{{ $index }}][buttons][{{ $btnIndex }}][link]"
                            class="form-control mb-2"
                            placeholder="Button Link"
                            value="{{
                                $button['link']
                                ?? ''
                            }}"
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

    {{-- Remove Section --}}
    <button
        type="button"
        class="btn btn-danger remove-section">
        Remove Section
    </button>
</div>