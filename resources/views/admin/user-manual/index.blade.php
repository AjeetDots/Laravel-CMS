@extends('layouts.admin')

@section('title', 'User Manual')

@section('styles')
<style>
    html:has(.manual-layout) {
        scroll-padding-top: calc(64px + 1.5rem);
    }
    .manual-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1.5rem;
    }
    @media (min-width: 992px) {
        .manual-layout {
            grid-template-columns: 220px minmax(0, 1fr);
            align-items: start;
        }
        .manual-toc {
            position: sticky;
            top: 1rem;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }
    }
    .manual-toc .nav-link {
        font-size: 0.82rem;
        padding: 0.35rem 0.65rem;
        color: #475569;
        border-radius: 6px;
    }
    .manual-toc .nav-link:hover,
    .manual-toc .nav-link.active {
        background: rgba(183, 152, 96, 0.15);
        color: #3d2f1d;
    }
    .manual-section {
        /* Keep section headings visible below the sticky admin topbar (64px) when using #anchors */
        scroll-margin-top: calc(64px + 1.5rem);
        padding-bottom: 2rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .manual-section > h2 {
        scroll-margin-top: calc(64px + 1.5rem);
    }
    .manual-section:last-child {
        border-bottom: 0;
        margin-bottom: 0;
    }
    .manual-figure {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        background: #fafbfc;
        margin: 1rem 0 1.25rem;
    }
    .manual-figure img {
        display: block;
        width: 100%;
        height: auto;
    }
    .manual-figure figcaption {
        padding: 0.65rem 1rem;
        font-size: 0.8rem;
        color: #64748b;
        background: #fff;
        border-top: 1px solid #e2e8f0;
    }
    .manual-callout {
        border-left: 4px solid #b79860;
        background: #faf8f4;
        padding: 0.85rem 1rem;
        border-radius: 0 8px 8px 0;
        margin: 1rem 0;
        font-size: 0.92rem;
    }
    .manual-callout--info {
        border-left-color: #0ea5e9;
        background: #f0f9ff;
    }
    .manual-callout--warn {
        border-left-color: #f59e0b;
        background: #fffbeb;
    }
    .manual-steps {
        counter-reset: step;
        list-style: none;
        padding-left: 0;
    }
    .manual-steps li {
        counter-increment: step;
        position: relative;
        padding-left: 2.5rem;
        margin-bottom: 0.75rem;
    }
    .manual-steps li::before {
        content: counter(step);
        position: absolute;
        left: 0;
        top: 0;
        width: 1.65rem;
        height: 1.65rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #b79860, #8f7447);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .manual-link-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.5rem;
    }
    .manual-link-grid a {
        display: block;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        text-decoration: none;
        color: #334155;
    }
    .manual-link-grid a:hover {
        border-color: #b79860;
        background: #faf8f4;
        color: #3d2f1d;
    }
    .manual-table th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #64748b;
    }
</style>
@endsection

@section('content')

<div class="page-header-bar">
    <div>
        <h1><i class="fas fa-book-open me-2 text-muted"></i>User Manual</h1>
        <p class="text-muted mb-0 small">A plain-language guide for updating your website without technical knowledge. Bookmark this page — it opens from <strong>User manual</strong> in the top bar, next to <strong>View site</strong>.</p>
    </div>
    <a href="{{ route('admin.user-manual.export') }}" class="btn btn-outline-primary btn-sm flex-shrink-0">
        <i class="fas fa-file-word me-1" aria-hidden="true"></i>Open in Microsoft Word
    </a>
</div>

<div class="manual-layout">
    <aside class="manual-toc card p-3" aria-label="Manual contents">
        <div class="fw-semibold small text-uppercase text-muted mb-2">On this page</div>
        <nav class="nav flex-column">
            <a class="nav-link" href="#intro">Welcome</a>
            <a class="nav-link" href="#account-security">Login, password &amp; account</a>
            <a class="nav-link" href="#admin-basics">Using the admin panel</a>
            <a class="nav-link" href="#module-visibility">Show or hide modules</a>
            <a class="nav-link" href="#site-map">How the site is built</a>
            <a class="nav-link" href="#services">Services</a>
            <a class="nav-link" href="#testimonials">Testimonials</a>
            <a class="nav-link" href="#brands">Brands</a>
            <a class="nav-link" href="#sliders">Sliders &amp; hero</a>
            <a class="nav-link" href="#other-content">Gallery, blog &amp; pages</a>
            <a class="nav-link" href="#content-hub">Content Hub</a>
            <a class="nav-link" href="#menus-logos">Menus &amp; logos</a>
            <a class="nav-link" href="#seo">SEO</a>
            <a class="nav-link" href="#communication">Messages &amp; email</a>
            <a class="nav-link" href="#tips">Tips &amp; when to call your developer</a>
            <a class="nav-link" href="#quick-links">Quick links</a>
        </nav>
    </aside>

    <div class="card">
        <div class="card-body p-3 p-lg-4">

            @include('admin.user-manual.partials.content', ['wordExport' => false])


        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
    var tocLinks = document.querySelectorAll('.manual-toc .nav-link');
    var sections = [];
    var scrollOffset = 84; // sticky topbar (64px) + small gap

    tocLinks.forEach(function (link) {
        var id = (link.getAttribute('href') || '').replace('#', '');
        if (id) {
            var el = document.getElementById(id);
            if (el) sections.push({ link: link, el: el });
        }
    });
    if (!sections.length) return;

    function scrollToSection(el, smooth) {
        var top = el.getBoundingClientRect().top + window.pageYOffset - scrollOffset;
        window.scrollTo({ top: Math.max(0, top), behavior: smooth ? 'smooth' : 'auto' });
    }

    tocLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            var id = (link.getAttribute('href') || '').replace('#', '');
            var el = id ? document.getElementById(id) : null;
            if (!el) return;
            e.preventDefault();
            scrollToSection(el, true);
            if (history.pushState) {
                history.pushState(null, '', '#' + id);
            } else {
                location.hash = id;
            }
            tocLinks.forEach(function (l) { l.classList.remove('active'); });
            link.classList.add('active');
        });
    });

    function onScroll() {
        var y = window.scrollY + scrollOffset + 8;
        var current = sections[0];
        sections.forEach(function (s) {
            if (s.el.offsetTop <= y) current = s;
        });
        tocLinks.forEach(function (l) { l.classList.remove('active'); });
        if (current) current.link.classList.add('active');
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    if (location.hash) {
        var target = document.getElementById(location.hash.slice(1));
        if (target) {
            window.requestAnimationFrame(function () {
                scrollToSection(target, false);
            });
        }
    }
})();
</script>
@endsection

