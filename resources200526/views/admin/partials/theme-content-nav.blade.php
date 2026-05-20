{{-- CMS-managed public pages ($active: home|finishes|services|gallery|portfolio|about|contact|newsletter_footer) --}}
@php $active = $active ?? 'home'; @endphp
<nav class="theme-content-nav card mb-4" aria-label="Content Hub">
    <div class="card-body py-3 px-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <span class="theme-content-nav__label">Content Hub</span>
            <ul class="nav theme-content-nav__pills mb-0">
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'home' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.home.index') }}"
                       @if($active === 'home') aria-current="page" @endif>
                        <i class="fas fa-house me-1" aria-hidden="true"></i> Home Page
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'finishes' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.finishes.index') }}"
                       @if($active === 'finishes') aria-current="page" @endif>
                        <i class="fas fa-palette me-1" aria-hidden="true"></i> Finishes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'services' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.services.index') }}"
                       @if($active === 'services') aria-current="page" @endif>
                        <i class="fas fa-concierge-bell me-1" aria-hidden="true"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'gallery' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.gallery.index') }}"
                       @if($active === 'gallery') aria-current="page" @endif>
                        <i class="fas fa-photo-video me-1" aria-hidden="true"></i> Gallery
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'portfolio' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.portfolio.index') }}"
                       @if($active === 'portfolio') aria-current="page" @endif>
                        <i class="fas fa-briefcase me-1" aria-hidden="true"></i> Portfolio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'about' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.about.index') }}"
                       @if($active === 'about') aria-current="page" @endif>
                        <i class="fas fa-address-card me-1" aria-hidden="true"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'contact' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.contact.index') }}"
                       @if($active === 'contact') aria-current="page" @endif>
                        <i class="fas fa-envelope me-1" aria-hidden="true"></i> Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $active === 'newsletter_footer' ? 'active' : '' }}"
                       href="{{ route('admin.theme-options.newsletter-footer.index') }}"
                       @if($active === 'newsletter_footer') aria-current="page" @endif>
                        <i class="fas fa-paper-plane me-1" aria-hidden="true"></i> Footer newsletter
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
