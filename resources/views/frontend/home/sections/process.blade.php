@php
    $processCfg = $processSection ?? [];
    $processEnabled = array_key_exists('is_enabled', $processCfg) ? !empty($processCfg['is_enabled']) : true;
    $processEyebrow = $processCfg['eyebrow'] ?? 'Our Process';
    $processHeadingLine1 = $processCfg['heading_line_1'] ?? 'From first conversation';
    $processHeadingLine2 = $processCfg['heading_line_2'] ?? 'to final polish.';
    $processSteps = is_array($processCfg['steps'] ?? null) ? $processCfg['steps'] : [];
@endphp

@if($processEnabled)
<section class="home-process section-white">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="eyebrow d-inline-block">{{ $processEyebrow }}</span>
            <h2 class="home-process-title mt-3 mb-0">{{ $processHeadingLine1 }}<br /> {{ $processHeadingLine2 }}</h2>
        </div>
        <ol class="home-process-steps list-unstyled mb-0">
            @foreach($processSteps as $index => $step)
            <li class="home-process-step reveal delay-{{ $index + 1 }}">
                <span class="home-process-step__num font-serif">{{ $step['num'] ?? '' }}</span>
                <h3 class="home-process-step__title">{{ $step['title'] ?? '' }}</h3>
                <p class="home-process-step__desc">{{ $step['desc'] ?? '' }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>
@endif
