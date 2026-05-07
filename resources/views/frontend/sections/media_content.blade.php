@php
    $hasImage = !empty($data['image']);
@endphp

@if($hasImage && $data['image_position'] == 'left')

    <div class="col-lg-6">
        <div class="media-frame about-feature-frame">
            <img
                src="{{ asset(
                    'storage/'
                    .$data['image']
                ) }}"
                alt="{{ $data['title'] }}"
                class="feature-img img-fluid"
            >
        </div>
    </div>

@endif



<div class="
    {{
        $hasImage
        ? 'col-lg-6'
        : 'col-lg-12'
    }}
    pt-lg-5
">

    <span class="story-label">
        {{ $data['title'] }}
    </span>

    {!! $data['content'] !!}

    @if(!empty($data['buttons']))
        <div class="d-flex gap-3 mt-4 flex-wrap">
            @foreach($data['buttons'] as $button)
                <a
                    href="{{
                        $button['link']
                    }}"
                    class="btn btn-dark">
                    {{
                        $button['text']
                    }}
                </a>
            @endforeach
        </div>
    @endif

</div>

@if($hasImage && $data['image_position'] == 'right')
    <div class="col-lg-6">
        <div class="media-frame about-feature-frame">
            <img
                src="{{ asset(
                    'storage/'
                    .$data['image']
                ) }}"
                alt="{{ $data['title'] }}"
                class="feature-img img-fluid"
            >
        </div>
    </div>

@endif