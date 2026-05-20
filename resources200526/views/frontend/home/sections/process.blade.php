@php
    $processCfg = $processSection ?? [];
    $processEnabled = array_key_exists('is_enabled', $processCfg) ? !empty($processCfg['is_enabled']) : true;
    $processEyebrow = $processCfg['eyebrow'] ?? '';
    $processHeadingLine1 = $processCfg['heading_line_1'] ?? '';
    $processHeadingLine2 = $processCfg['heading_line_2'] ?? '';
    $processSteps = is_array($processCfg['steps'] ?? null) ? $processCfg['steps'] : [];
@endphp

@if($processEnabled)
<section class="home-process section-white">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="finishes-intro__eyebrow ">{{ $processEyebrow }}</span>
            <h2 class="home-atelier-headline">{{ $processHeadingLine1 }}<br /> {{ $processHeadingLine2 }}</h2>
        </div>
        <ol class="home-process-steps list-unstyled mb-0">
            @foreach($processSteps as $index => $step)
            @continue(empty(trim((string) ($step['num'] ?? ''))) && empty(trim((string) ($step['title'] ?? ''))) && empty(trim((string) ($step['desc'] ?? ''))))
            <li class="home-process-step reveal delay-{{ $index + 1 }}">
                <span class="home-process-step__num font-serif">{{ $step['num'] ?? '' }}</span>
                <h3 class="home-why-card__title-dark">{{ $step['title'] ?? '' }}</h3>
                <p class="home-process-step__desc">{{ $step['desc'] ?? '' }}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>
@endif
