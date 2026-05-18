@php
    $variant = $variant ?? 'default';
@endphp
<style>
    .cms-page-skin {
        --cps-ink: #211f1c;
        --cps-muted: #5c564e;
        --cps-gold: #aa8453;
        --cps-cream: #f8f6f1;
        --cps-rule: rgba(170, 132, 83, 0.25);
        --cps-read: min(100%, 42rem);
    }

    .cms-page-skin .cms-page-body {
        padding: clamp(2.5rem, 5vw, 4rem) 0 clamp(3.5rem, 7vw, 5rem);
        background: var(--cps-cream);
    }

    .cms-page-skin .cms-page-body .page-content {
        font-size: 1.0625rem;
        line-height: 1.78;
        color: var(--cps-ink);
    }

    .cms-page-skin .cms-page-body .page-content h2,
    .cms-page-skin .cms-page-body .page-content h3 {
        font-family: 'Georgia Regular', Georgia, serif;
        font-weight: 400;
        color: var(--cps-ink);
        margin-top: 2rem;
        margin-bottom: 0.75rem;
    }

    .cms-page-skin .cms-page-body .page-content a {
        color: var(--cps-gold);
    }

    .cms-page-skin .cms-page-body .cms-page-blocks {
        display: flex;
        flex-direction: column;
        gap: clamp(1.75rem, 3vw, 2.5rem);
    }

    .cms-page-skin .cms-page-body .cms-page-block-row {
        padding: clamp(1.25rem, 2.5vw, 1.5rem);
        background: var(--cps-cream);
        border: 1px solid var(--cps-rule);
        border-radius: 10px;
    }

    .cms-page-skin .cms-page-body .cms-page-block-row .story-label {
        display: block;
        font-size: 0.7rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--cps-gold);
        margin-bottom: 0.65rem;
    }

    .cms-page-skin .cms-page-body .cms-page-block-row .media-frame,
    .cms-page-skin .cms-page-body .cms-page-block-row .cms-section-media__frame {
        border-radius: 8px;
        overflow: hidden;
    }

    .cms-page-skin .cms-page-body .cms-page-intro + .cms-page-blocks,
    .cms-page-skin .cms-page-body .cms-page-blocks + .cms-page-intro {
        margin-top: clamp(2rem, 4vw, 2.75rem);
        padding-top: clamp(1.75rem, 3vw, 2.25rem);
        border-top: 1px solid var(--cps-rule);
    }

    .cms-page-skin .cms-page-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        background: var(--cps-cream);
        border: 1px dashed var(--cps-rule);
        border-radius: 10px;
        color: var(--cps-muted);
    }

    .cms-page-skin--default .cms-page-body__inner {
        max-width: var(--cps-read);
        margin-left: auto;
        margin-right: auto;
    }

    .cms-page-skin--default .cms-page-body .cms-page-block-row [class*="col-"] {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .cms-page-skin--full .cms-page-body {
        background: var(--cps-cream);
    }

    .cms-page-skin--full .cms-builder__shell {
        max-width: 1140px;
    }

    .cms-page-skin--full .cms-builder-band {
        background: #fff;
        border-radius: 10px;
        border: 1px solid var(--cps-rule);
        padding: clamp(1.25rem, 2.5vw, 1.75rem);
        margin-bottom: 1.25rem;
    }

    .cms-page-skin--full .cms-builder-band--alt {
        background: #fdfcfa;
    }

    .cms-page-skin--sidebar .cms-page-body {
        background: var(--cps-cream);
    }

    .cms-page-skin--sidebar .cms-builder-sidebar__grid {
        align-items: flex-start;
    }

    .cms-page-skin--sidebar .cms-builder-aside .cms-page-sidebar {
        position: sticky;
        top: 7.5rem;
    }
</style>
