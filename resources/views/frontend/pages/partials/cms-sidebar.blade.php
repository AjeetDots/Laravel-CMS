<aside class="cms-page-sidebar cms-sidebar-rail" aria-label="Page sidebar">
    @if(trim((string) ($page->sidebar_content ?? '')) !== '')
        <div class="cms-sidebar-panel cms-sidebar-panel--custom page-content cms-sidebar-custom mb-4">
            {!! $page->sidebar_content !!}
        </div>
    @endif

    <div class="cms-sidebar-panel cms-sidebar-panel--contact">
        <p class="cms-sidebar-panel__eyebrow">Get in touch</p>

        @if($page->resolvedSidebarCtaTitle() !== '')
            <h2 class="cms-sidebar-panel__title">{{ $page->resolvedSidebarCtaTitle() }}</h2>
        @endif

        @if($page->resolvedSidebarCtaText() !== '')
            <p class="cms-sidebar-panel__text">{{ $page->resolvedSidebarCtaText() }}</p>
        @endif

        <ul class="cms-sidebar-contact-list list-unstyled mb-0">
            @if(\App\Support\SitePhone::hasPhone($settings))
                <li class="cms-sidebar-contact-item">
                    <span class="cms-sidebar-contact-item__icon" aria-hidden="true">
                        <i class="fas fa-phone"></i>
                    </span>
                    <span class="cms-sidebar-contact-item__body">
                        <span class="cms-sidebar-contact-item__label">Phone</span>
                        <a href="tel:{{ \App\Support\SitePhone::telHref($settings) }}">{{ \App\Support\SitePhone::display($settings) }}</a>
                    </span>
                </li>
            @endif

            @if(trim((string) $settings->get('site_email')) !== '')
                <li class="cms-sidebar-contact-item">
                    <span class="cms-sidebar-contact-item__icon" aria-hidden="true">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <span class="cms-sidebar-contact-item__body">
                        <span class="cms-sidebar-contact-item__label">Email</span>
                        <a href="mailto:{{ $settings->get('site_email') }}">{{ $settings->get('site_email') }}</a>
                    </span>
                </li>
            @endif
        </ul>

        <a href="{{ route('contact') }}" class="btn btn-dark cms-sidebar-panel__cta w-100">Contact us</a>
    </div>
</aside>
