<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $settings->get('site_name','ProServices')) — {{ $settings->get('site_name','ProServices') }}</title>
    <meta name="description" content="@yield('meta_description', $settings->get('site_tagline',''))">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400;1,600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
    /* ═══════════════════════════════════════
       DESIGN TOKENS
    ═══════════════════════════════════════ */
    :root {
        --cream:        #FAFAF7;
        --cream-dark:   #F4EFE6;
        --ink:          #2c2824;
        --ink-mid:      #4a453e;
        --ink-light:    #8a837a;
        --wine:         #c07850;
        --wine-dark:    #a05a38;
        --wine-light:   #fdf0e8;
        --gold:         #c9a87c;
        --gold-light:   #fdf5e8;
        --border:       #e8e0d5;
        --white:        #ffffff;
        /* legacy aliases */
        --blue:         #c07850;
        --blue-dark:    #a05a38;
        --blue-light:   #fdf0e8;
        --violet:       #c9a87c;
        --gradient:     linear-gradient(135deg, #c07850 0%, #d4906a 100%);
        --gradient-glow:radial-gradient(ellipse at 60% 40%, rgba(192,120,80,.18) 0%, transparent 65%);
        /* Logo slot — sized to nearly fill the nav row (square PNGs read much larger) */
        --nav-logo-max-height: 140px;
        --nav-logo-max-height-scrolled: 110px;
        --nav-logo-max-width: min(480px, 54vw);
        --footer-logo-max-height: 100px;
        --footer-logo-max-width: min(320px, 88vw);
    }

    /* ═══════════════════════════════════════
       BASE
    ═══════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
        font-family: 'Inter', sans-serif;
        background: var(--white);
        color: var(--ink);
        font-size: 16px;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }
    h1, h2, h3, h4, h5 {
        font-family: 'Playfair Display', Georgia, serif;
        letter-spacing: 0;
    }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; }
    /* Navbar/footer logos: don't inherit shrinking from generic img rule + flex */
    .nav-logo-img,
    .footer-logo-img { max-width: none; }

    /* ═══════════════════════════════════════
       NAVBAR — FIXED TRANSPARENT OVER HERO
    ═══════════════════════════════════════ */
    .site-nav {
        position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
        background: linear-gradient(to bottom,
            rgba(7,5,3,.86) 0%,
            rgba(7,5,3,.58) 50%,
            transparent 100%);
        border-bottom: 1px solid transparent;
        transition: background .45s ease, border-color .45s ease, box-shadow .45s ease, backdrop-filter .45s ease;
    }

    /* Solid white once user scrolls */
    .site-nav.scrolled {
        background: rgba(255,255,255,.97);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom-color: var(--border);
        box-shadow: 0 4px 32px rgba(0,0,0,.08);
    }

    /* Non-hero pages always solid (added via body class) */
    .nav-solid .site-nav,
    .site-nav.always-solid {
        background: rgba(255,255,255,.97);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom-color: var(--border);
    }

    /* Thin gold top accent — visible only when scrolled/solid */
    .site-nav::before {
        content: '';
        display: block; height: 2px;
        background: linear-gradient(90deg, var(--gold) 0%, var(--wine) 50%, var(--gold) 100%);
        opacity: 0; transition: opacity .4s;
    }
    .site-nav.scrolled::before,
    .site-nav.always-solid::before { opacity: 1; }

    /* Single main row */
    .nav-main-row { padding: 0; }
    .nav-main-row .container {
        display: flex; align-items: center;
        min-height: 150px; height: 150px; gap: 0;
        transition: height .35s ease, min-height .35s ease;
    }
    .site-nav.scrolled .nav-main-row .container,
    .site-nav.always-solid .nav-main-row .container { min-height: 122px; height: 122px; }

    /* Logo / brand — left */
    .nav-brand {
        display: inline-flex; align-items: center;
        text-decoration: none; flex-shrink: 0; margin-right: 32px;
        max-width: min(480px, 54vw);
    }

    .nav-brand-text {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.4rem; font-weight: 700; letter-spacing: 3px;
        text-transform: uppercase; color: #fff; line-height: 1;
        text-shadow: 0 2px 12px rgba(0,0,0,.6);
    }
    .site-nav.scrolled .nav-brand-text,
    .site-nav.always-solid .nav-brand-text { color: var(--ink); text-shadow: none; }

    .nav-brand-tagline {
        font-size: .55rem; letter-spacing: 3.5px; text-transform: uppercase;
        color: rgba(255,255,255,.7); font-weight: 500; transition: color .4s;
        text-shadow: 0 1px 6px rgba(0,0,0,.5);
    }
    .site-nav.scrolled .nav-brand-tagline,
    .site-nav.always-solid .nav-brand-tagline { color: var(--ink-light); text-shadow: none; }

    .nav-logo-img {
        height: auto; width: auto;
        max-height: var(--nav-logo-max-height);
        max-width: var(--nav-logo-max-width);
        flex-shrink: 0;
        object-fit: contain;
        object-position: left center;
        display: block;
        transition: transform .35s ease, filter .4s, max-height .35s ease, max-width .35s ease;
        filter: drop-shadow(0 4px 20px rgba(0,0,0,.45));
    }
    .site-nav.scrolled .nav-logo-img,
    .site-nav.always-solid .nav-logo-img {
        max-height: var(--nav-logo-max-height-scrolled);
        filter: none;
    }
    .nav-logo-img:hover { transform: translateY(-1px) scale(1.02); }

    @media (max-width: 1199px) {
        :root { --nav-logo-max-height: 118px; --nav-logo-max-height-scrolled: 96px; --nav-logo-max-width: min(400px, 56vw); }
        .nav-main-row .container { min-height: 128px; height: 128px; }
        .site-nav.scrolled .nav-main-row .container,
        .site-nav.always-solid .nav-main-row .container { min-height: 108px; height: 108px; }
    }
    .footer-brand {
        margin-bottom: 8px;
    }
    .footer-logo-link {
        display: inline-block;
        line-height: 0;
        transition: opacity .25s, transform .25s;
    }
    .footer-logo-link:hover { opacity: .92; transform: translateY(-1px); }
    .footer-logo-img {
        height: auto; width: auto;
        max-height: var(--footer-logo-max-height);
        max-width: var(--footer-logo-max-width);
        width: auto;
        object-fit: contain;
        object-position: left center;
        display: block;
        margin-bottom: 22px;
        transition: filter .35s;
        filter: drop-shadow(0 2px 12px rgba(0,0,0,.35));
    }
    .footer-logo-img:hover {
        filter: brightness(1.08) drop-shadow(0 4px 20px rgba(201,168,76,.35));
    }
    @media (max-width: 991px) {
        :root { --footer-logo-max-height: 88px; --footer-logo-max-width: min(280px, 90vw); }
    }

    /* Nav links — center (flex-grow) */
    .nav-links {
        display: flex; align-items: center; justify-content: center;
        flex: 1; gap: 0; list-style: none; margin: 0; padding: 0;
    }
    .nav-links > li { position: relative; }
    .nav-links > li > a {
        position: relative;
        display: block; padding: 12px 14px;
        font-size: .72rem; font-weight: 700; letter-spacing: 1.55px;
        text-transform: uppercase; color: #fff;
        text-shadow: 0 2px 10px rgba(0,0,0,.5);
        transition: color .25s, text-shadow .25s; white-space: nowrap;
    }
    .nav-links > li > a::after {
        content: '';
        position: absolute; left: 14px; right: 14px; bottom: 4px;
        height: 2px; border-radius: 2px;
        background: linear-gradient(90deg, var(--gold), var(--wine));
        transform: scaleX(0); transform-origin: center;
        transition: transform .24s ease;
    }
    .site-nav.scrolled .nav-links > li > a,
    .site-nav.always-solid .nav-links > li > a { color: var(--ink-mid); }

    .nav-links > li > a:hover,
    .nav-links > li > a.active { color: var(--gold) !important; }
    .nav-links > li > a:hover::after,
    .nav-links > li > a.active::after { transform: scaleX(1); }
    .site-nav.scrolled .nav-links > li > a:hover,
    .site-nav.scrolled .nav-links > li > a.active,
    .site-nav.always-solid .nav-links > li > a:hover,
    .site-nav.always-solid .nav-links > li > a.active { color: var(--wine) !important; }

    /* Dropdown */
    .nav-links .has-drop:hover .drop-menu { opacity: 1; pointer-events: all; transform: translateY(0); }
    .drop-menu {
        position: absolute; top: calc(100% + 1px); left: 0;
        background: #fff; border: 1px solid var(--border);
        border-top: 2px solid var(--wine);
        padding: 10px 0; min-width: 220px;
        opacity: 0; pointer-events: none; transform: translateY(-6px);
        border-radius: 6px;
        transition: all .2s; box-shadow: 0 18px 44px rgba(0,0,0,.13);
    }
    .drop-menu a {
        display: block; padding: 10px 20px;
        font-size: .72rem; font-weight: 500; letter-spacing: 1px;
        text-transform: uppercase; color: var(--ink-mid);
        transition: color .2s, background .2s;
    }
    .drop-menu a:hover { background: var(--cream-dark); color: var(--wine); }

    /* Right actions */
    .nav-right {
        display: flex; align-items: center; gap: 14px; flex-shrink: 0; margin-left: 18px;
    }
    .nav-top-contacts { display: flex; gap: 16px; align-items: center; }
    .nav-top-contacts a {
        color: rgba(255,255,255,.75); display: inline-flex; align-items: center; gap: 5px;
        transition: color .2s; font-size: .7rem; letter-spacing: .3px;
    }
    .site-nav.scrolled .nav-top-contacts a,
    .site-nav.always-solid .nav-top-contacts a { color: var(--ink-light); }
    .nav-top-contacts a:hover { color: var(--gold); }
    .nav-top-contacts i { color: var(--gold); font-size: .68rem; }
    .site-nav.scrolled .nav-top-contacts i,
    .site-nav.always-solid .nav-top-contacts i { color: var(--wine); }

    .nav-top-right { display: flex; gap: 10px; align-items: center; }
    .nav-top-right a {
        color: rgba(255,255,255,.75); font-size: .85rem; transition: color .2s;
    }
    .site-nav.scrolled .nav-top-right a,
    .site-nav.always-solid .nav-top-right a { color: var(--ink-light); }
    .nav-top-right a:hover { color: var(--gold); }

    .btn-quote {
        background: linear-gradient(135deg, var(--wine) 0%, #cf8a62 100%);
        color: #fff !important;
        font-size: .65rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        padding: 11px 20px; border-radius: 2px;
        transition: background .25s, box-shadow .25s, border-color .25s, transform .25s;
        border: 1px solid rgba(255,255,255,.36); white-space: nowrap;
        box-shadow: 0 8px 24px rgba(0,0,0,.2);
    }
    /* slightly outlined style when nav is transparent */
    .site-nav:not(.scrolled):not(.always-solid) .btn-quote {
        background: transparent;
        border-color: rgba(255,255,255,.6);
    }
    .site-nav:not(.scrolled):not(.always-solid) .btn-quote:hover {
        background: rgba(255,255,255,.18);
        border-color: #fff;
    }
    .site-nav.scrolled .btn-quote,
    .site-nav.always-solid .btn-quote {
        border-color: var(--wine);
        box-shadow: 0 6px 18px rgba(160,90,56,.25);
    }
    .btn-quote:hover {
        background: linear-gradient(135deg, var(--wine-dark) 0%, #b66e49 100%);
        box-shadow: 0 10px 24px rgba(160,90,56,.32);
        transform: translateY(-1px);
    }

    /* Mobile toggle */
    .nav-toggle {
        display: none; background: none; border: 1px solid rgba(255,255,255,.4);
        padding: 6px 12px; cursor: pointer; color: #fff; font-size: 1rem;
        margin-left: auto; transition: border-color .4s, color .4s;
    }
    .site-nav.scrolled .nav-toggle,
    .site-nav.always-solid .nav-toggle { border-color: var(--border); color: var(--ink); }

    @media (max-width: 991px) {
        :root { --nav-logo-max-height: 100px; --nav-logo-max-height-scrolled: 82px; --nav-logo-max-width: min(300px, 78vw); }
        .nav-main-row .container { flex-wrap: wrap; min-height: auto; height: auto; padding-top: 14px; padding-bottom: 14px; }
        .nav-brand { margin-right: auto; }
        .nav-toggle { display: flex; align-items: center; }
        .nav-links, .nav-right { display: none; width: 100%; }
        .nav-links.open {
            display: flex; flex-direction: column; align-items: flex-start;
            gap: 0; border-top: 1px solid rgba(255,255,255,.2); margin-top: 10px;
            background: rgba(20,12,6,.92); backdrop-filter: blur(10px);
        }
        .site-nav.scrolled .nav-links.open,
        .site-nav.always-solid .nav-links.open {
            background: #fff; border-top-color: var(--border);
        }
        .nav-links.open > li { width: 100%; }
        .nav-links.open > li > a { padding: 13px 16px; border-bottom: 1px solid rgba(255,255,255,.1); width: 100%; }
        .nav-links.open > li > a::after { left: 16px; right: 16px; bottom: 8px; }
        .site-nav.scrolled .nav-links.open > li > a,
        .site-nav.always-solid .nav-links.open > li > a { border-bottom-color: var(--border); }
        .nav-right.open { display: flex; padding: 12px 0; }
        .nav-top-contacts { display: none; }
        .nav-logo-img { max-height: var(--nav-logo-max-height) !important; height: auto !important; }
        .drop-menu { position: static; opacity: 1; transform: none; box-shadow: none; border: none; background: rgba(255,255,255,.08); padding-left: 16px; border-top: none; }
        .site-nav.scrolled .drop-menu,
        .site-nav.always-solid .drop-menu { background: var(--cream-dark); }
    }

    /* Legacy refs kept for compatibility */
    .nav-top-bar { display: none; }
    .nav-brand-row { display: none; }

    /* ═══════════════════════════════════════
       3-PANEL HERO SLIDER
    ═══════════════════════════════════════ */
    .hero3 {
        display: grid;
        grid-template-columns: 260px 1fr 220px;
        height: 560px;
        overflow: hidden;
        background: var(--ink);
    }

    /* LEFT decorative panel */
    .hero3-left {
        background: var(--cream-dark);
        position: relative; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        border-right: 1px solid var(--border);
    }
    .hero3-left::before {
        content: '';
        position: absolute; inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Ccircle cx='40' cy='40' r='28' fill='none' stroke='%23c9a87c' stroke-width='.6' opacity='.3'/%3E%3Ccircle cx='40' cy='40' r='14' fill='none' stroke='%23c9a87c' stroke-width='.4' opacity='.2'/%3E%3Cline x1='40' y1='0' x2='40' y2='80' stroke='%23c9a87c' stroke-width='.3' opacity='.15'/%3E%3Cline x1='0' y1='40' x2='80' y2='40' stroke='%23c9a87c' stroke-width='.3' opacity='.15'/%3E%3C/svg%3E");
        background-size: 80px 80px;
    }
    .hero3-left-inner {
        position: relative; z-index: 2;
        padding: 48px 28px;
        display: flex; flex-direction: column;
        align-items: center; text-align: center;
    }
    .hero3-leaf {
        font-family: Georgia, serif; font-size: 6rem;
        color: var(--gold); opacity: .2; line-height: 1;
        margin-bottom: 24px; user-select: none;
    }
    .hero3-eyebrow-left {
        font-size: .58rem; font-weight: 800; letter-spacing: 4.5px;
        text-transform: uppercase; color: var(--wine);
        margin-bottom: 28px;
    }
    .hero3-counter {
        display: flex; align-items: baseline; gap: 4px; margin-bottom: 24px;
    }
    .hero3-current {
        font-family: 'Playfair Display', serif;
        font-size: 4rem; font-weight: 700;
        color: var(--ink); line-height: 1;
        transition: opacity .3s;
    }
    .hero3-sep { font-size: .9rem; color: var(--border); margin: 0 4px; }
    .hero3-total { font-size: 1.1rem; color: var(--ink-light); }
    .hero3-progs { display: flex; flex-direction: column; gap: 5px; align-items: center; }
    .hero3-prog {
        width: 2px; height: 20px;
        background: var(--border);
        transition: background .4s, height .4s;
    }
    .hero3-prog.active { background: var(--wine); height: 40px; }
    .hero3-scroll-hint {
        margin-top: 36px; display: flex; flex-direction: column;
        align-items: center; gap: 8px;
    }
    .hero3-scroll-hint span {
        font-size: .6rem; font-weight: 700; letter-spacing: 3px;
        text-transform: uppercase; color: var(--ink-light);
        writing-mode: horizontal-tb;
    }
    .hero3-scroll-line {
        width: 1px; height: 40px; background: var(--border);
        position: relative; overflow: hidden;
    }
    .hero3-scroll-line::after {
        content: ''; position: absolute; top: -100%;
        left: 0; right: 0; height: 100%;
        background: var(--wine);
        animation: scrollAnim 1.8s ease-in-out infinite;
    }
    @keyframes scrollAnim {
        0%   { top: -100%; }
        100% { top: 100%; }
    }

    /* CENTER main slide */
    .hero3-center { position: relative; overflow: hidden; }
    .hero3-slide {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        opacity: 0;
    }
    .hero3-slide.active {
        opacity: 1;
        animation: hero3Blink .85s ease forwards;
    }
    @keyframes hero3Blink {
        0%   { opacity: 0; transform: scale(1.03); filter: brightness(1.5) saturate(.4); }
        25%  { opacity: .9; filter: brightness(1.2) saturate(.7); }
        100% { opacity: 1; transform: scale(1);    filter: brightness(1) saturate(1); }
    }
    .hero3-center-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(130deg,
            rgba(10,6,3,.80) 0%,
            rgba(30,18,8,.42) 55%,
            transparent 100%
        );
    }
    .hero3-body {
        position: absolute; inset: 0; z-index: 2;
        display: flex; align-items: flex-end;
        padding: 0 0 72px 60px;
    }
    .hero3-body-inner { max-width: 560px; }
    .hero3-tag {
        display: block; font-size: .62rem; font-weight: 700;
        letter-spacing: 3.5px; text-transform: uppercase;
        color: var(--gold); margin-bottom: 18px;
    }
    .hero3-title {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2rem, 3.2vw, 3.2rem);
        font-weight: 700; color: #fff;
        line-height: 1.15; margin-bottom: 0;
    }
    .hero3-btns { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 30px; }
    .hero-btn {
        display: inline-flex; align-items: center; gap: 10px;
        background: var(--wine); color: #fff;
        font-size: .72rem; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase;
        padding: 14px 32px; border-radius: 0; border: 1px solid var(--wine);
        transition: background .2s, box-shadow .2s;
    }
    .hero-btn:hover { background: var(--wine-dark); color: #fff; box-shadow: 0 6px 20px rgba(107,16,64,.35); }
    .hero-btn-outline {
        display: inline-flex; align-items: center; gap: 10px;
        border: 1px solid rgba(255,255,255,.55); color: #fff;
        font-size: .72rem; font-weight: 700; letter-spacing: 2px;
        text-transform: uppercase; padding: 13px 32px; border-radius: 0;
        transition: all .2s; background: transparent;
    }
    .hero-btn-outline:hover { background: rgba(255,255,255,.12); color: #fff; }
    .hero3-arrow {
        position: absolute; bottom: 24px; z-index: 10;
        width: 44px; height: 44px; border-radius: 0;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
        color: #fff; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all .2s; font-size: .85rem;
    }
    .hero3-arrow:hover { background: var(--wine); border-color: var(--wine); }
    .hero3-prev { right: 58px; }
    .hero3-next { right: 12px; }

    /* RIGHT two-stacked thumbnails */
    .hero3-right {
        display: flex; flex-direction: column; overflow: hidden;
        border-left: 2px solid rgba(255,255,255,.06);
    }
    .hero3-right-cell {
        flex: 1; position: relative; overflow: hidden;
    }
    .hero3-right-cell + .hero3-right-cell {
        border-top: 2px solid rgba(255,255,255,.06);
    }
    .hero3-thumb {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        transition: transform .6s ease;
    }
    .hero3-right-cell:hover .hero3-thumb { transform: scale(1.07); }
    .hero3-thumb-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(20,10,5,.75) 0%, rgba(10,5,2,.15) 55%, transparent 100%);
        display: flex; align-items: flex-end; padding: 16px 14px;
        transition: background .3s;
    }
    .hero3-right-cell:hover .hero3-thumb-overlay {
        background: linear-gradient(to top, rgba(160,90,56,.75) 0%, rgba(160,90,56,.2) 60%, transparent 100%);
    }
    .hero3-thumb-label {
        font-size: .65rem; font-weight: 700; letter-spacing: 1.5px;
        text-transform: uppercase; color: rgba(255,255,255,.88);
        text-decoration: none; display: block;
    }
    .hero3-thumb-label:hover { color: #fff; }

    /* Responsive */
    @media (max-width: 767px) {
        .hero3 { grid-template-columns: 1fr; height: 520px; }
        .hero3-left, .hero3-right { display: none; }
        .hero3-body { padding: 0 0 56px 24px; }
    }
    @media (min-width: 768px) and (max-width: 1099px) {
        .hero3 { grid-template-columns: 200px 1fr; height: 560px; }
        .hero3-right { display: none; }
    }
    @media (min-width: 1100px) and (max-width: 1299px) {
        .hero3 { grid-template-columns: 230px 1fr 190px; height: 520px; }
    }

    /* ═══════════════════════════════════════
       SECTION SCAFFOLDING
    ═══════════════════════════════════════ */
    .section { padding: 100px 0; }
    .section-sm { padding: 70px 0; }
    .section-dark { background: var(--ink); color: #fff; }
    .section-soft { background: var(--cream-dark); }
    .section-white { background: var(--white); }

    .section-header { margin-bottom: 64px; }
    .eyebrow {
        display: inline-block;
        font-family: 'Inter', sans-serif;
        font-size: .68rem; font-weight: 700;
        letter-spacing: 3.5px; text-transform: uppercase;
        color: var(--wine); margin-bottom: 14px;
    }
    .eyebrow-light { color: rgba(255,255,255,.55); }
    .section-rule {
        width: 50px; height: 2px;
        background: var(--wine); margin: 18px 0 28px;
        display: block;
    }
    .section-rule.centered { margin-left: auto; margin-right: auto; }
    .section-rule.gold { background: var(--gold); }
    .section-header h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.9rem, 4vw, 2.8rem);
        font-weight: 700; letter-spacing: 0; line-height: 1.2;
        color: var(--ink); margin-bottom: 0;
    }
    .section-header h2.light { color: #fff; }
    .section-header p {
        font-size: 1rem; color: var(--ink-light);
        max-width: 540px; line-height: 1.8; margin: 0;
    }
    .section-header p.light { color: rgba(255,255,255,.65); }
    .hr-rule { border: none; border-top: 1px solid var(--border); }

    /* ═══════════════════════════════════════
       SERVICES
    ═══════════════════════════════════════ */
    /* IMAGE-FORWARD SERVICE CARD (like L&R) */
    .svc-card {
        position: relative; overflow: hidden;
        aspect-ratio: 3/4; background: var(--ink);
        cursor: pointer; display: block; color: #fff;
    }
    .svc-card-img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .6s ease;
    }
    .svc-card:hover .svc-card-img { transform: scale(1.07); }
    .svc-card-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(20,10,8,.80) 0%, rgba(20,10,8,.30) 50%, transparent 100%);
        transition: background .4s;
    }
    .svc-card:hover .svc-card-overlay {
        background: linear-gradient(to top, rgba(107,16,64,.85) 0%, rgba(107,16,64,.40) 60%, transparent 100%);
    }
    .svc-card-body {
        position: absolute; bottom: 0; left: 0; right: 0; padding: 28px 24px;
    }
    .svc-card-body h4 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.15rem; font-weight: 600; color: #fff; margin-bottom: 8px;
        text-transform: uppercase; letter-spacing: 1px;
    }
    .svc-card-rule {
        width: 28px; height: 1.5px; background: var(--gold);
        margin-bottom: 10px; transition: width .3s;
    }
    .svc-card:hover .svc-card-rule { width: 50px; }
    .svc-card-desc {
        font-size: .85rem; color: rgba(255,255,255,.75); line-height: 1.7;
        max-height: 0; overflow: hidden;
        transition: max-height .4s ease, opacity .4s;
        opacity: 0;
    }
    .svc-card:hover .svc-card-desc { max-height: 80px; opacity: 1; }
    /* Fallback icon-based card (when no image) */
    .svc-card-plain {
        padding: 40px 32px; background: var(--white);
        border: 1px solid var(--border); border-top: 3px solid var(--wine);
        height: 100%; transition: box-shadow .3s, transform .3s;
    }
    .svc-card-plain:hover { box-shadow: 0 12px 40px rgba(107,16,64,.12); transform: translateY(-4px); }
    .svc-icon {
        width: 52px; height: 52px; margin-bottom: 22px;
        display: flex; align-items: center; justify-content: center;
        color: var(--wine); font-size: 1.4rem;
        border-bottom: 1px solid var(--border); padding-bottom: 18px;
    }
    .svc-card-plain h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem; font-weight: 600; color: var(--ink); margin-bottom: 10px;
    }
    .svc-card-plain p { font-size: .88rem; color: var(--ink-light); line-height: 1.8; margin: 0; }
    .svc-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .72rem; font-weight: 700; letter-spacing: 2px;
        text-transform: uppercase; color: var(--wine); margin-top: 18px;
        border-bottom: 1px solid transparent; transition: border-color .2s, gap .2s;
    }
    .svc-link:hover { border-bottom-color: var(--wine); gap: 10px; }

    /* ═══════════════════════════════════════
       GALLERY
    ═══════════════════════════════════════ */
    .gal-item {
        position: relative; border-radius: 8px; overflow: hidden;
        aspect-ratio: 1; background: var(--cream-dark);
    }
    .gal-item img { width: 100%; height: 100%; object-fit: cover; transition: transform .45s; }
    .gal-item:hover img { transform: scale(1.06); }
    .gal-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(15,15,13,.75) 0%, transparent 60%);
        opacity: 0; transition: opacity .3s;
        display: flex; align-items: flex-end; padding: 20px;
    }
    .gal-item:hover .gal-overlay { opacity: 1; }
    .gal-overlay span { color: #fff; font-weight: 700; font-size: .9rem; }

    /* ═══════════════════════════════════════
       TESTIMONIALS
    ═══════════════════════════════════════ */
    .testi-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 36px;
        height: 100%;
    }
    .testi-stars { color: #f59e0b; margin-bottom: 18px; font-size: .95rem; }
    .testi-quote { font-size: .95rem; color: var(--ink-mid); line-height: 1.85; font-style: italic; margin-bottom: 26px; }
    .testi-author { display: flex; align-items: center; gap: 14px; }
    .testi-avatar {
        width: 46px; height: 46px; border-radius: 50%;
        background: var(--blue-light); color: var(--blue);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1rem; overflow: hidden; flex-shrink: 0;
    }
    .testi-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .testi-name { font-weight: 800; font-size: .92rem; color: var(--ink); }
    .testi-role { font-size: .8rem; color: var(--ink-light); }

    /* ═══════════════════════════════════════
       BRANDS
    ═══════════════════════════════════════ */
    .brand-track {
        display: flex; gap: 48px; align-items: center;
        animation: brandScroll 22s linear infinite;
        width: max-content;
    }
    .brand-track:hover { animation-play-state: paused; }
    @keyframes brandScroll { from { transform: translateX(0); } to { transform: translateX(-50%); } }
    .brand-logo-item {
        flex-shrink: 0;
        filter: grayscale(1); opacity: .45;
        transition: all .3s; cursor: default;
    }
    .brand-logo-item:hover { filter: grayscale(0); opacity: 1; }
    .brand-logo-item img { height: 40px; width: auto; object-fit: contain; }
    .brand-logo-item .brand-placeholder {
        height: 40px; padding: 0 20px;
        background: var(--cream-dark); border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; color: var(--ink-light); letter-spacing: 1px; text-transform: uppercase;
        white-space: nowrap;
    }

    /* ═══════════════════════════════════════
       STATS BAND
    ═══════════════════════════════════════ */
    .stats-band {
        background: #2c2824;
        padding: 70px 0;
        position: relative; overflow: hidden;
    }
    .stats-band::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(ellipse at 50% 50%, rgba(201,168,76,.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .stat-item { text-align: center; padding: 10px 20px; position: relative; }
    .stat-num {
        display: block; font-family: 'Playfair Display', serif;
        font-size: 3.2rem; font-weight: 700; line-height: 1;
        color: var(--gold);
    }
    .stat-num span { color: rgba(255,255,255,.5); font-family: 'Inter', sans-serif; font-size: 1.8rem; }
    .stat-lbl { font-size: .68rem; font-weight: 600; letter-spacing: 2.5px; text-transform: uppercase; color: rgba(255,255,255,.45); margin-top: 10px; display: block; }
    .stat-sep { width: 1px; background: rgba(255,255,255,.12); align-self: stretch; }

    /* ═══════════════════════════════════════
       CONTACT — PREMIUM
    ═══════════════════════════════════════ */

    /* Wrapper — generous vertical space */
    .contact-wrap { padding: 80px 0 100px; }

    /* ── Info Panel ── */
    .contact-info-panel {
        background: #18130e;
        border-radius: 20px;
        padding: 52px 44px 48px;
        height: 100%;
        color: #fff;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(201,168,76,.12);
        box-shadow: 0 32px 64px rgba(0,0,0,.28), inset 0 1px 0 rgba(201,168,76,.18);
    }
    /* Warm radial glow */
    .contact-info-panel::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse at 85% 10%, rgba(192,120,80,.22) 0%, transparent 55%),
            radial-gradient(ellipse at 10% 90%, rgba(201,168,76,.12) 0%, transparent 45%);
        pointer-events: none;
    }
    /* Gold top accent bar */
    .contact-info-panel::after {
        content: '';
        position: absolute;
        top: 0; left: 44px; right: 44px;
        height: 2px;
        background: linear-gradient(90deg, var(--gold), var(--wine), transparent);
        border-radius: 0 0 2px 2px;
    }
    .contact-info-panel > * { position: relative; z-index: 1; }

    /* Availability badge */
    .cinfo-avail {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(74,222,128,.08);
        border: 1px solid rgba(74,222,128,.2);
        border-radius: 100px;
        padding: 6px 14px;
        margin-bottom: 32px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #4ade80;
    }
    .cinfo-avail-dot {
        width: 7px; height: 7px;
        background: #4ade80;
        border-radius: 50%;
        box-shadow: 0 0 8px #4ade80;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .5; transform: scale(.75); }
    }

    .contact-info-panel .cinfo-heading {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 2.1rem;
        font-weight: 700;
        line-height: 1.2;
        color: #fff;
        margin-bottom: 12px;
        letter-spacing: -.01em;
    }
    .contact-info-panel .cinfo-heading em {
        font-style: italic;
        color: var(--gold);
    }
    .contact-info-panel .sub {
        font-size: .875rem;
        color: rgba(255,255,255,.48);
        margin-bottom: 40px;
        line-height: 1.7;
    }

    /* Divider */
    .cinfo-divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(201,168,76,.25), transparent);
        margin: 0 0 32px;
    }

    .cinfo-row {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 28px;
    }
    .cinfo-icon {
        width: 46px; height: 46px;
        border: 1.5px solid rgba(201,168,76,.28);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--gold);
        font-size: .95rem;
        flex-shrink: 0;
        background: rgba(201,168,76,.06);
        transition: background .25s, border-color .25s;
    }
    .cinfo-row:hover .cinfo-icon {
        background: rgba(201,168,76,.14);
        border-color: rgba(201,168,76,.55);
    }
    .cinfo-label {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,.3);
        margin-bottom: 5px;
    }
    .cinfo-val {
        font-size: .93rem;
        font-weight: 500;
        color: rgba(255,255,255,.88);
        line-height: 1.45;
    }

    /* Social row */
    .cinfo-social-label {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255,255,255,.28);
        margin-bottom: 14px;
    }
    .cinfo-social-links { display: flex; gap: 10px; flex-wrap: wrap; }
    .cinfo-social-link {
        width: 40px; height: 40px;
        border: 1.5px solid rgba(255,255,255,.1);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,.45);
        font-size: .9rem;
        transition: all .25s;
    }
    .cinfo-social-link:hover {
        border-color: var(--gold);
        color: var(--gold);
        background: rgba(201,168,76,.1);
        transform: translateY(-2px);
    }

    /* ── Form Panel ── */
    .contact-form-panel {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 52px 48px 48px;
        box-shadow: 0 8px 48px rgba(44,40,36,.06), 0 1px 0 rgba(44,40,36,.04);
        position: relative;
    }
    /* Gold left accent bar */
    .contact-form-panel::before {
        content: '';
        position: absolute;
        top: 36px; bottom: 36px; left: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--gold), var(--wine), transparent);
        border-radius: 0 2px 2px 0;
    }

    .contact-form-panel .cfp-eyebrow {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--wine);
        margin-bottom: 10px;
    }
    .contact-form-panel h3 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 6px;
        letter-spacing: -.01em;
        line-height: 1.2;
    }
    .contact-form-panel .sub {
        font-size: .875rem;
        color: var(--ink-light);
        margin-bottom: 36px;
        line-height: 1.65;
    }

    /* Gold underline decoration under form heading */
    .cfp-heading-deco {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 32px;
    }
    .cfp-heading-deco-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, var(--border), transparent);
    }
    .cfp-heading-deco-dot {
        width: 6px; height: 6px;
        background: var(--gold);
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Form inputs */
    .form-label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--ink-mid);
        margin-bottom: 7px;
    }
    .form-control, .form-select {
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 13px 16px;
        font-size: .93rem;
        background: #fdfcfa;
        color: var(--ink);
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .form-control::placeholder { color: #b5aea6; }
    .form-control:hover { border-color: #d4c9bc; background: #fff; }
    .form-control:focus, .form-select:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 4px rgba(201,168,76,.1);
        background: #fff;
        outline: none;
    }
    textarea.form-control { min-height: 140px; resize: vertical; }

    /* Submit button override for contact */
    .contact-submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, var(--wine) 0%, #d4906a 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 15px 36px;
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .5px;
        cursor: pointer;
        transition: all .25s;
        box-shadow: 0 4px 20px rgba(192,120,80,.3);
    }
    .contact-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 32px rgba(192,120,80,.4);
        background: linear-gradient(135deg, #d4906a 0%, var(--wine) 100%);
    }
    .contact-submit-btn .btn-arrow {
        width: 28px; height: 28px;
        background: rgba(255,255,255,.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem;
        transition: transform .25s;
    }
    .contact-submit-btn:hover .btn-arrow { transform: translateX(4px); }

    /* ═══════════════════════════════════════
       PAGE HEADER (inner pages)
    ═══════════════════════════════════════ */
    /* body offset — fixed nav (solid on inner pages ≈120px + breathing room) */
    body:not(.has-hero) { padding-top: 138px; }

    .page-hero,
    .page-header {
        background: var(--ink);
        padding: 88px 0 76px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before,
    .page-header::before {
        content: '';
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 72% 20%, rgba(192,120,80,.28) 0%, transparent 55%),
            radial-gradient(ellipse at 15% 85%, rgba(201,168,76,.14) 0%, transparent 45%),
            linear-gradient(180deg, rgba(44,40,36,.4) 0%, transparent 45%);
        pointer-events: none;
    }
    .page-hero .container,
    .page-header .container { position: relative; z-index: 1; }
    .page-hero .eyebrow,
    .page-header .eyebrow {
        display: inline-block;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 14px;
        opacity: .95;
    }
    .page-hero .eyebrow::before,
    .page-header .eyebrow::before {
        content: '';
        display: block;
        width: 40px;
        height: 2px;
        background: linear-gradient(90deg, var(--gold), var(--wine));
        margin-bottom: 14px;
        border-radius: 2px;
    }
    .page-hero h1,
    .page-header h1 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(2.1rem, 4.2vw, 3.15rem);
        font-weight: 700;
        letter-spacing: -0.5px;
        line-height: 1.12;
        margin-bottom: 16px;
        position: relative;
        max-width: min(100%, 22rem);
    }
    .page-hero p,
    .page-header p {
        font-size: 1.05rem;
        color: rgba(255,255,255,.68);
        position: relative;
        max-width: 36rem;
        line-height: 1.65;
        margin-bottom: 0;
    }
    .page-hero .blog-hero-meta {
        display: flex; flex-wrap: wrap; gap: 18px; align-items: center;
        margin-top: 14px;
        font-size: .84rem;
        color: rgba(255,255,255,.58);
        position: relative;
    }
    .page-hero .blog-hero-meta i { color: var(--gold); opacity: .85; }
    .breadcrumb { background: none; padding: 0; margin-top: 22px; position: relative; }
    .breadcrumb-item, .breadcrumb-item a { color: rgba(255,255,255,.5); font-size: .8rem; font-weight: 600; letter-spacing: .5px; }
    .breadcrumb-item.active { color: rgba(255,255,255,.85); }
    .breadcrumb-item+.breadcrumb-item::before { color: rgba(255,255,255,.3); }

    .page-hero h1.page-hero-title-wide,
    .page-header h1.page-hero-title-wide { max-width: min(100%, 42rem); }

    /* Gallery filter chips (replaces inline styles) */
    .gallery-filter-bar { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 2.5rem; }
    .gallery-filter-btn {
        font-size: .72rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase;
        padding: 10px 20px; border-radius: 6px; border: 1.5px solid var(--border);
        background: transparent; color: var(--ink-light); cursor: pointer;
        transition: background .2s, color .2s, border-color .2s, box-shadow .2s;
    }
    .gallery-filter-btn:hover {
        border-color: var(--wine); color: var(--wine);
    }
    .gallery-filter-btn.active {
        background: var(--ink); color: #fff; border-color: var(--ink);
        box-shadow: 0 6px 20px rgba(44,40,36,.15);
    }

    /* CMS / legal pages */
    .page-content {
        font-size: 1.05rem; line-height: 1.9; color: var(--ink-mid);
    }
    .page-content h2, .page-content h3, .page-content h4 {
        font-family: 'Playfair Display', Georgia, serif;
        color: var(--ink);
        margin-top: 1.75em; margin-bottom: .65em;
        font-weight: 700;
    }
    .page-content h2 { font-size: 1.65rem; }
    .page-content h3 { font-size: 1.25rem; }
    .page-content p { margin-bottom: 1.15em; }
    .page-content ul, .page-content ol { margin-bottom: 1.15em; padding-left: 1.35em; }
    .page-content a { color: var(--wine); text-decoration: underline; text-underline-offset: 3px; }
    .page-content a:hover { color: var(--wine-dark); }

    /* Service detail */
    .service-detail-lead-img {
        border-radius: 12px; overflow: hidden; margin-bottom: 40px; max-height: 420px;
    }
    .service-detail-lead-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .service-detail-body { font-size: 1.02rem; line-height: 1.9; color: var(--ink-mid); }
    .service-sidebar-wrap { position: sticky; top: 132px; }
    .service-sidebar-card {
        background: var(--cream-dark); border: 1px solid var(--border); border-radius: 12px; padding: 32px;
    }
    .service-sidebar-card h4 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.25rem; font-weight: 700; color: var(--ink); margin-bottom: 12px;
    }
    .service-sidebar-card .sub { font-size: .9rem; color: var(--ink-light); line-height: 1.8; margin-bottom: 24px; }
    .service-sidebar-more-label {
        font-size: .7rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        color: var(--ink-light); margin-bottom: 16px;
    }
    .service-sidebar-link {
        display: flex; align-items: center; gap: 12px; padding: 12px 0;
        border-bottom: 1px solid var(--border); color: var(--ink-mid);
        font-size: .92rem; font-weight: 600; transition: color .2s;
    }
    .service-sidebar-link:last-child { border-bottom: none; }
    .service-sidebar-link:hover { color: var(--wine); }
    .service-sidebar-link i { color: var(--wine); width: 18px; text-align: center; }

    /* ═══════════════════════════════════════
       CTA STRIP
    ═══════════════════════════════════════ */
    .cta-strip {
        background: #2c2824;
        padding: 90px 0;
        color: #fff;
        position: relative; overflow: hidden;
    }
    .cta-strip::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 0 L60 30 L30 60 L0 30 Z' fill='none' stroke='rgba(255,255,255,.04)' stroke-width='1'/%3E%3C/svg%3E") repeat;
        pointer-events: none;
    }
    .cta-strip .container { position: relative; z-index: 1; }
    .cta-strip h2 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem); font-weight: 600;
        letter-spacing: 0; margin-bottom: 14px;
    }
    .cta-strip p { font-size: 1rem; opacity: .8; margin-bottom: 0; }
    .btn-white {
        display: inline-flex; align-items: center; gap: 10px;
        background: #fff; color: var(--wine);
        font-size: .72rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        padding: 15px 34px; border-radius: 0;
        transition: all .25s; border: 1px solid #fff;
    }
    .btn-white:hover { background: transparent; color: #fff; }
    .btn-outline-white {
        display: inline-flex; align-items: center; gap: 10px;
        border: 1px solid rgba(255,255,255,.6); color: #fff;
        font-size: .72rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        padding: 14px 34px; border-radius: 0; background: transparent;
        transition: all .2s;
    }
    .btn-outline-white:hover { border-color: #fff; background: rgba(255,255,255,.1); }

    /* ═══════════════════════════════════════
       PRIMARY BUTTON
    ═══════════════════════════════════════ */
    .btn-primary-site {
        display: inline-flex; align-items: center; gap: 10px;
        background: var(--wine); color: #fff;
        font-size: .72rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        padding: 14px 32px; border-radius: 0; border: 1px solid var(--wine);
        transition: background .2s, box-shadow .2s; cursor: pointer;
    }
    .btn-primary-site:hover { background: var(--wine-dark); color: #fff; box-shadow: 0 6px 20px rgba(107,16,64,.25); }
    .btn-outline-site {
        display: inline-flex; align-items: center; gap: 10px;
        border: 1px solid var(--wine); color: var(--wine);
        font-size: .72rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
        padding: 13px 32px; border-radius: 0; background: transparent;
        transition: all .2s;
    }
    .btn-outline-site:hover { background: var(--wine); color: #fff; }

    /* ═══════════════════════════════════════
       FOOTER
    ═══════════════════════════════════════ */
    .site-footer {
        background: linear-gradient(180deg, #2f2a26 0%, #25211e 100%);
        color: rgba(255,255,255,.62);
        padding: 72px 0 28px;
        position: relative;
    }
    .site-footer::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--gold) 0%, #e8c878 50%, var(--gold) 100%);
    }
    .site-footer .row.g-5 { --bs-gutter-y: 2.5rem; }
    .site-footer .brand-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.45rem; font-weight: 700; color: #fff;
        letter-spacing: 2px; text-transform: uppercase;
        margin-bottom: 8px; display: block;
    }
    .site-footer .brand-tagline-footer {
        font-size: .62rem; letter-spacing: 3px; text-transform: uppercase;
        color: rgba(255,255,255,.42); margin-bottom: 20px; display: block;
    }
    .site-footer .about-text {
        font-size: .9rem; line-height: 1.85; max-width: 300px;
        color: rgba(255,255,255,.58);
    }
    .site-footer h6 {
        font-family: 'Inter', sans-serif;
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: 2.8px;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 22px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(201,168,124,.28);
    }
    .site-footer .footer-nav a {
        color: rgba(255,255,255,.58);
        font-size: .9rem;
        display: block;
        margin-bottom: 12px;
        transition: color .2s, padding-left .2s;
        padding-left: 0;
    }
    .site-footer .footer-nav a:hover {
        color: #fff;
        padding-left: 4px;
    }
    .site-footer .footer-nav a:last-child { margin-bottom: 0; }
    .footer-contact-line {
        display: flex; align-items: flex-start; gap: 12px;
        font-size: .9rem; color: rgba(255,255,255,.58);
        margin-bottom: 14px; line-height: 1.55;
    }
    .footer-contact-line:last-child { margin-bottom: 0; }
    .footer-contact-line a {
        color: rgba(255,255,255,.58); display: inline; margin: 0;
        transition: color .2s;
    }
    .footer-contact-line a:hover { color: var(--gold); }
    .footer-contact-line i {
        color: var(--gold);
        width: 18px;
        text-align: center;
        margin-top: 3px;
        flex-shrink: 0;
        opacity: .9;
    }
    .footer-social {
        display: flex; flex-wrap: wrap; gap: 10px;
    }
    .footer-social a {
        display: inline-flex; width: 42px; height: 42px;
        background: rgba(255,255,255,.07);
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 10px;
        align-items: center; justify-content: center;
        font-size: .95rem;
        transition: all .22s;
        color: rgba(255,255,255,.55) !important;
    }
    .footer-social a:hover {
        background: var(--gold);
        border-color: var(--gold);
        color: var(--ink) !important;
        transform: translateY(-2px);
    }
    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,.1);
        padding: 20px 0 0;
        margin-top: 48px;
        font-size: .8rem;
        color: rgba(255,255,255,.38);
    }

    /* ═══════════════════════════════════════
       ALERTS
    ═══════════════════════════════════════ */
    .alert-success { background: #dcfce7; color: #15803d; border: none; border-radius: 10px; font-size: .9rem; }
    .alert-danger  { background: #fee2e2; color: #b91c1c; border: none; border-radius: 10px; font-size: .9rem; }

    /* ═══════════════════════════════════════
       SCROLL REVEAL ANIMATIONS
    ═══════════════════════════════════════ */
    .reveal {
        opacity: 0;
        transform: translateY(36px);
        transition: opacity .65s ease, transform .65s ease;
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-left  { opacity: 0; transform: translateX(-36px); transition: opacity .65s ease, transform .65s ease; }
    .reveal-left.visible { opacity: 1; transform: translateX(0); }
    .reveal-right { opacity: 0; transform: translateX(36px); transition: opacity .65s ease, transform .65s ease; }
    .reveal-right.visible { opacity: 1; transform: translateX(0); }
    .delay-1 { transition-delay: .1s; }
    .delay-2 { transition-delay: .2s; }
    .delay-3 { transition-delay: .3s; }
    .delay-4 { transition-delay: .4s; }
    .delay-5 { transition-delay: .5s; }

    /* ═══════════════════════════════════════
       FLOATING BADGE ANIMATION
    ═══════════════════════════════════════ */
    @keyframes floatY {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-8px); }
    }
    .float-anim { animation: floatY 3.5s ease-in-out infinite; }

    /* ═══════════════════════════════════════
       TESTIMONIAL CARD UPGRADE
    ═══════════════════════════════════════ */
    .testi-card {
        transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s;
    }
    .testi-card:hover { transform: translateY(-6px); box-shadow: 0 16px 48px rgba(67,97,238,.12); }
    .testi-avatar { background: var(--gradient); color: #fff; }

    /* ═══════════════════════════════════════
       GALLERY UPGRADE
    ═══════════════════════════════════════ */
    .gal-item { border-radius: 14px; }
    .gal-overlay {
        background: linear-gradient(to top, rgba(180,83,9,.82) 0%, transparent 65%);
    }

    /* ═══════════════════════════════════════
       BLOG CARDS
    ═══════════════════════════════════════ */
    .blog-card {
        display: block; background: var(--white);
        border: 1px solid var(--border);
        overflow: hidden; height: 100%;
        transition: box-shadow .35s, transform .35s;
        color: var(--ink);
    }
    .blog-card:hover {
        box-shadow: 0 16px 48px rgba(44,40,36,.12);
        transform: translateY(-5px);
        color: var(--ink);
    }
    .blog-card-img-wrap {
        position: relative; overflow: hidden;
        aspect-ratio: 4/3; background: var(--cream-dark);
    }
    .blog-card-img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .55s ease;
    }
    .blog-card:hover .blog-card-img { transform: scale(1.07); }
    .blog-card-img-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: var(--border); font-size: 2.5rem;
    }
    .blog-badge {
        position: absolute; top: 14px; left: 14px;
        background: var(--wine); color: #fff;
        font-size: .62rem; font-weight: 700; letter-spacing: 1.5px;
        text-transform: uppercase; padding: 5px 12px;
    }
    .blog-card-body { padding: 26px 24px 28px; }
    .blog-card-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.05rem; font-weight: 600; color: var(--ink);
        margin-bottom: 10px; line-height: 1.45;
    }
    .blog-card-excerpt {
        font-size: .87rem; color: var(--ink-light);
        line-height: 1.75; margin-bottom: 14px;
    }
    .blog-card-meta {
        display: flex; gap: 16px;
        font-size: .75rem; color: var(--ink-light);
        margin-bottom: 16px; flex-wrap: wrap;
    }
    .blog-read-more {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: .7rem; font-weight: 700; letter-spacing: 1.8px;
        text-transform: uppercase; color: var(--wine);
        border-bottom: 1px solid var(--wine); padding-bottom: 1px;
        transition: gap .2s;
    }
    .blog-card:hover .blog-read-more { gap: 10px; }

    /* ═══════════════════════════════════════
       BLOG POST CONTENT
    ═══════════════════════════════════════ */
    .blog-post-content {
        font-size: 1.02rem; line-height: 1.9;
        color: var(--ink-mid);
    }
    .blog-post-content p { margin-bottom: 1.4rem; }

    /* ═══════════════════════════════════════
       NEWSLETTER SECTION
    ═══════════════════════════════════════ */
    .newsletter-section {
        position: relative; padding: 110px 0;
        background-color: #2a1f14;
        background-size: cover; background-position: center;
        overflow: hidden;
    }
    .newsletter-section::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg,
            rgba(30,18,8,.88) 0%,
            rgba(44,32,18,.72) 50%,
            rgba(30,18,8,.88) 100%);
    }
    /* Subtle botanical SVG pattern overlay */
    .newsletter-section::after {
        content: '';
        position: absolute; inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Ccircle cx='60' cy='60' r='40' fill='none' stroke='rgba(201,168,76,.06)' stroke-width='1'/%3E%3Ccircle cx='60' cy='60' r='20' fill='none' stroke='rgba(201,168,76,.04)' stroke-width='1'/%3E%3Cline x1='60' y1='0' x2='60' y2='120' stroke='rgba(201,168,76,.03)' stroke-width='1'/%3E%3Cline x1='0' y1='60' x2='120' y2='60' stroke='rgba(201,168,76,.03)' stroke-width='1'/%3E%3C/svg%3E");
        background-size: 120px 120px;
        pointer-events: none;
    }
    .newsletter-section .container { position: relative; z-index: 2; }
    .newsletter-eyebrow {
        display: inline-block; font-size: .65rem; font-weight: 700;
        letter-spacing: 4px; text-transform: uppercase;
        color: var(--gold); margin-bottom: 16px;
    }
    .newsletter-section h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.9rem, 4vw, 3rem); font-weight: 700;
        color: #fff; margin-bottom: 12px; line-height: 1.2;
    }
    .newsletter-section p { font-size: .97rem; color: rgba(255,255,255,.58); margin-bottom: 36px; }
    .newsletter-form { display: flex; gap: 0; max-width: 500px; }
    .newsletter-input {
        flex: 1; padding: 15px 20px;
        background: rgba(255,255,255,.09); border: 1px solid rgba(201,168,76,.35);
        border-right: none; color: #fff; font-size: .93rem;
        outline: none; transition: background .2s, border-color .2s;
        border-radius: 0;
    }
    .newsletter-input::placeholder { color: rgba(255,255,255,.35); }
    .newsletter-input:focus { background: rgba(255,255,255,.14); border-color: var(--gold); }
    .newsletter-btn {
        padding: 15px 28px; background: var(--gold); color: var(--ink);
        font-size: .7rem; font-weight: 800; letter-spacing: 2px;
        text-transform: uppercase; border: none; cursor: pointer;
        white-space: nowrap; transition: background .2s;
        border-radius: 0;
    }
    .newsletter-btn:hover { background: #e0bc88; }
    .newsletter-leaf {
        position: absolute; pointer-events: none; z-index: 1;
        opacity: .12;
    }
    .newsletter-leaf.left {
        left: -40px; top: 50%; transform: translateY(-50%);
        font-size: 14rem; color: var(--gold);
        font-family: serif; line-height: 1;
    }
    .newsletter-leaf.right {
        right: -40px; bottom: -20px;
        font-size: 14rem; color: var(--gold);
        font-family: serif; line-height: 1;
    }
    @media (max-width: 576px) {
        .newsletter-form { flex-direction: column; }
        .newsletter-input { border-right: 1px solid rgba(201,168,76,.35); border-bottom: none; }
        .newsletter-btn { width: 100%; }
    }

    /* ═══════════════════════════════════════
       RESPONSIVE UTILITIES
    ═══════════════════════════════════════ */
    @media (max-width: 768px) {
        .section { padding: 64px 0; }
        .section-sm { padding: 48px 0; }
        .section-header { margin-bottom: 40px; }
    }
    </style>
    @yield('styles')
</head>
<body class="@yield('body_class')">

{{-- ── NAVBAR ────────────────────────────────────────────────── --}}
<nav class="site-nav" id="siteNav">
    <div class="nav-main-row">
        <div class="container">

            {{-- Logo / Brand --}}
            <a href="{{ route('home') }}" class="nav-brand">
                @if($settings->get('site_logo'))
                    <img src="{{ asset('storage/' . $settings->get('site_logo')) }}"
                         alt="{{ $settings->get('site_name', 'ProServices') }}" class="nav-logo-img">
                @else
                    <span class="nav-brand-text">{{ $settings->get('site_name', 'ProServices') }}</span>
                    @if($settings->get('site_tagline'))
                        <span class="nav-brand-tagline">{{ $settings->get('site_tagline') }}</span>
                    @endif
                @endif
            </a>

            {{-- Nav links (center) --}}
            <ul class="nav-links" id="navLinks">
                @foreach($navMenus as $menu)
                    @if($menu->children->count())
                        <li class="has-drop">
                            <a href="{{ $menu->url ?? '#' }}"
                               class="{{ request()->is(ltrim($menu->url??'','/').'*') ? 'active' : '' }}">
                                {{ $menu->label }}
                                <i class="fas fa-chevron-down ms-1" style="font-size:.5rem;opacity:.6;"></i>
                            </a>
                            <div class="drop-menu">
                                @foreach($menu->children as $child)
                                    <a href="{{ $child->url }}" target="{{ $child->target }}">{{ $child->label }}</a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li>
                            <a href="{{ $menu->url ?? '#' }}" target="{{ $menu->target ?? '_self' }}"
                               class="{{ request()->is(ltrim($menu->url??'','/').'*') || ($menu->url=='/' && request()->is('/')) ? 'active' : '' }}">
                                {{ $menu->label }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>

            {{-- Right: contact info + social + CTA --}}
            <div class="nav-right" id="navRight">
                <div class="nav-top-contacts d-none d-xl-flex">
                    @if($settings->get('site_phone'))
                        <a href="tel:{{ $settings->get('site_phone') }}">
                            <i class="fas fa-phone"></i> {{ $settings->get('site_phone') }}
                        </a>
                    @endif
                </div>
                <div class="nav-top-right d-none d-lg-flex">
                    @if($settings->get('social_instagram'))<a href="{{ $settings->get('social_instagram') }}" target="_blank"><i class="fab fa-instagram"></i></a>@endif
                    @if($settings->get('social_facebook'))<a href="{{ $settings->get('social_facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>@endif
                </div>
                <a href="{{ route('contact') }}" class="btn-quote">Get Quote</a>
            </div>

            <button class="nav-toggle" id="navToggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>

        </div>
    </div>
</nav>

{{-- ── PAGE CONTENT ──────────────────────────────────────────── --}}
@if(session('success'))
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif
@if(session('error'))
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@yield('content')

{{-- ── FOOTER ────────────────────────────────────────────────── --}}
<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            {{-- Brand --}}
            <div class="col-lg-4">
                <div class="footer-brand">
                @php $footerLogo = $settings->get('site_logo_footer') ?: $settings->get('site_logo'); @endphp
                @if($footerLogo)
                    <a href="{{ route('home') }}" class="footer-logo-link" aria-label="{{ $settings->get('site_name','ProServices') }} — Home">
                        <img src="{{ asset('storage/' . $footerLogo) }}" alt="{{ $settings->get('site_name','ProServices') }}" class="footer-logo-img" loading="lazy" decoding="async">
                    </a>
                @else
                    <span class="brand-name">{{ $settings->get('site_name','ProServices') }}</span>
                    @if($settings->get('site_tagline'))
                        <span class="brand-tagline-footer">{{ $settings->get('site_tagline') }}</span>
                    @endif
                @endif
                </div>
                <p class="about-text">{{ $settings->get('footer_about','Craft-led plaster, media walls, and architectural finishes for discerning homes and commercial spaces.') }}</p>
                <div class="footer-social mt-4">
                    @if($settings->get('social_facebook'))
                        <a href="{{ $settings->get('social_facebook') }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($settings->get('social_twitter'))
                        <a href="{{ $settings->get('social_twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if($settings->get('social_linkedin'))
                        <a href="{{ $settings->get('social_linkedin') }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                    @if($settings->get('social_instagram'))
                        <a href="{{ $settings->get('social_instagram') }}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
            </div>
            {{-- Services --}}
            <div class="col-6 col-lg-2">
                <h6>Services</h6>
                <nav class="footer-nav" aria-label="Services">
                    <a href="{{ route('services') }}">Venetian Plaster</a>
                    <a href="{{ route('services') }}">Bespoke Media Walls</a>
                    <a href="{{ route('services') }}">Cornices &amp; Mouldings</a>
                    <a href="{{ route('services') }}">Feature Walls</a>
                    <a href="{{ route('services') }}">Restoration</a>
                </nav>
            </div>
            {{-- Quick Links --}}
            <div class="col-6 col-lg-2">
                <h6>Company</h6>
                <nav class="footer-nav" aria-label="Company">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="/about">About Us</a>
                    <a href="{{ route('gallery') }}">Gallery</a>
                    <a href="/faq">FAQ</a>
                    <a href="{{ route('contact') }}">Contact</a>
                </nav>
            </div>
            {{-- Contact --}}
            <div class="col-lg-4">
                <h6>Get in touch</h6>
                @if($settings->get('site_email'))
                <div class="footer-contact-line">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:{{ $settings->get('site_email') }}">{{ $settings->get('site_email') }}</a>
                </div>
                @endif
                @if($settings->get('site_phone'))
                <div class="footer-contact-line">
                    <i class="fas fa-phone" aria-hidden="true"></i>
                    <a href="tel:{{ $settings->get('site_phone') }}">{{ $settings->get('site_phone') }}</a>
                </div>
                @endif
                @if($settings->get('site_address'))
                <div class="footer-contact-line">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <span>{{ $settings->get('site_address') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>&copy; {{ date('Y') }} {{ $settings->get('site_name','ProServices') }}. All rights reserved.</span>
            <span>Built with <i class="fas fa-heart" style="color:var(--gold); font-size:.8rem;"></i> using Laravel</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mobile nav
document.getElementById('navToggle').addEventListener('click', function() {
    document.getElementById('navLinks').classList.toggle('open');
    document.getElementById('navRight').classList.toggle('open');
});

// Navbar: transparent over hero, solid after scroll / on inner pages
(function() {
    const nav   = document.getElementById('siteNav');
    const hero  = document.getElementById('hero');
    const NAV_H = 152; /* 2px top accent + ~150px main row (see .nav-main-row .container) */

    // Non-hero pages get solid nav immediately
    if (!hero) { nav.classList.add('always-solid'); return; }

    function onScroll() {
        // Become solid once hero scrolls out of view (hero height minus nav)
        const heroBottom = hero.getBoundingClientRect().bottom;
        nav.classList.toggle('scrolled', heroBottom <= NAV_H + 20);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();

// 3-Panel hero slider
(function() {
    const slides  = document.querySelectorAll('.hero3-slide');
    const progs   = document.querySelectorAll('.hero3-prog');
    const counter = document.querySelector('.hero3-current');
    if (!slides.length) return;
    let current = 0, timer;

    function goTo(n) {
        slides[current].classList.remove('active');
        progs[current]?.classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        progs[current]?.classList.add('active');
        if (counter) counter.textContent = String(current + 1).padStart(2, '0');
    }
    function start() { timer = setInterval(() => goTo(current + 1), 5500); }
    function reset() { clearInterval(timer); start(); }

    document.querySelector('.hero3-prev')?.addEventListener('click', () => { goTo(current - 1); reset(); });
    document.querySelector('.hero3-next')?.addEventListener('click', () => { goTo(current + 1); reset(); });
    start();
})();

// Scroll reveal
(function() {
    const els = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
    if (!els.length) return;
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
    }, { threshold: 0.12 });
    els.forEach(el => io.observe(el));
})();

// Animated counters for stats
(function() {
    const nums = document.querySelectorAll('[data-count]');
    if (!nums.length) return;
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const el = e.target;
            const target = +el.dataset.count;
            const suffix = el.dataset.suffix || '';
            const dur = 1800;
            const step = 16;
            const inc = target / (dur / step);
            let cur = 0;
            const t = setInterval(() => {
                cur = Math.min(cur + inc, target);
                el.textContent = Math.floor(cur) + suffix;
                if (cur >= target) clearInterval(t);
            }, step);
            io.unobserve(el);
        });
    }, { threshold: 0.5 });
    nums.forEach(el => io.observe(el));
})();
</script>
@yield('scripts')
</body>
</html>
