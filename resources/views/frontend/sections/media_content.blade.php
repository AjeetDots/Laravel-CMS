@if($data['image_position'] == 'left')

    <div class="col-lg-6">
        <div class="media-frame about-feature-frame">
            <img
                src="{{ asset('storage/' . $data['image']) }}"
                alt="{{ $data['title'] }}"
                class="feature-img img-fluid"
            >
        </div>
    </div>

@endif



<div class="col-lg-6 pt-lg-5">
    <span class="story-label">
         {{ $data['title'] }}
    </span>

    {!! $data['content'] !!}
</div>



@if($data['image_position'] == 'right')

    <div class="col-lg-6">
        <div class="media-frame about-feature-frame">
            <img
                src="{{ asset('storage/' . $data['image']) }}"
                alt="{{ $data['title'] }}"
                class="feature-img img-fluid"
            >
        </div>
    </div>

@endif