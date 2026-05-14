<aside class="cms-page-sidebar" aria-label="Page sidebar">
    @if(trim((string) ($page->sidebar_content ?? '')) !== '')
        <div class="cms-sidebar-card cms-sidebar-card--custom page-content cms-sidebar-custom mb-4">
            {!! $page->sidebar_content !!}
        </div>
    @endif

    <div class="cms-sidebar-card">
        @if($page->resolvedSidebarCtaTitle() !== '')
        <h2 class="cms-sidebar-card__title">{{ $page->resolvedSidebarCtaTitle() }}</h2>
        @endif
        @if($page->resolvedSidebarCtaText() !== '')
        <p class="cms-sidebar-card__text mb-4">{{ $page->resolvedSidebarCtaText() }}</p>
        @endif

        @if(\App\Support\SitePhone::hasPhone($settings))
            <p class="cms-sidebar-card__line mb-2">
                <i class="fas fa-phone me-2 text-secondary" aria-hidden="true"></i>
                <a href="tel:{{ \App\Support\SitePhone::telHref($settings) }}">{{ \App\Support\SitePhone::display($settings) }}</a>
            </p>
        @endif

        @if(trim((string) $settings->get('site_email')) !== '')
            <p class="cms-sidebar-card__line mb-4">
                <i class="fas fa-envelope me-2 text-secondary" aria-hidden="true"></i>
                <a href="mailto:{{ $settings->get('site_email') }}">{{ $settings->get('site_email') }}</a>
            </p>
        @endif

        <a href="{{ route('contact') }}" class="btn btn-dark w-100 cms-sidebar-card__cta">Contact us</a>
    </div>
</aside>
