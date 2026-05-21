<?php

namespace App\Support;

final class CmsModuleRegistry
{
    /**
     * CMS content modules that can be shown or hidden on the public site.
     *
     * @return array<string, array{label: string, tooltip: string, affects: string}>
     */
    public static function definitions(): array
    {
        return [
            'sliders' => [
                'label' => 'Show hero sliders on website',
                'tooltip' => 'When checked, active slides from this module appear in the large hero banner on the home page. Uncheck to hide the entire hero area (your home page will start with the next section). Items marked Inactive in the list are never shown.',
                'affects' => 'Home page hero banner',
            ],
            'services' => [
                'label' => 'Show services on website',
                'tooltip' => 'When checked, active services appear on the home page (Services section) and on the Services listing and detail pages (/services). Uncheck to hide all of these from visitors while keeping your data in the admin.',
                'affects' => 'Home Services section, Services listing & detail pages',
            ],
            'finishes' => [
                'label' => 'Show finishes on website',
                'tooltip' => 'When checked, active finishes appear in the home Finishes preview and on the Finishes listing and detail pages. Uncheck to hide them from the public site. You can also hide only the home block in Content Hub → Home → Finishes.',
                'affects' => 'Home Finishes section, Finishes listing & detail pages',
            ],
            'portfolio' => [
                'label' => 'Show portfolio on website',
                'tooltip' => 'When checked, active portfolio projects appear on the Portfolio listing and detail pages. Uncheck to hide the whole portfolio area from visitors. Page headings are still edited in Content Hub → Portfolio page.',
                'affects' => 'Portfolio listing & detail pages',
            ],
            'gallery' => [
                'label' => 'Show gallery on website',
                'tooltip' => 'When checked, the Gallery page (/gallery) and the home “selected work” strip (Commissions section) can display active gallery images. Uncheck to hide the gallery page and that home strip. Categories are only used when the gallery is visible.',
                'affects' => 'Gallery page, home Commissions / selected work strip',
            ],
            'testimonials' => [
                'label' => 'Show testimonials on website',
                'tooltip' => 'When checked, active testimonials with a message can appear in the home testimonials slider. Uncheck to hide the whole testimonials block. Section titles and images are still set in Content Hub → Home → Testimonials.',
                'affects' => 'Home testimonials slider',
            ],
            'brands' => [
                'label' => 'Show brands on website',
                'tooltip' => 'When checked, active brand logos can appear in the partner strip near the bottom of the home page. Uncheck to hide the strip. Strip title and layout are edited in Content Hub → Home → Brands Strip.',
                'affects' => 'Home brands / partners strip',
            ],
            'blog' => [
                'label' => 'Show blog on website',
                'tooltip' => 'When checked, active blog posts appear on /blog, category pages, post detail pages, and the home blog preview section. Uncheck to hide all blog pages from visitors. Categories only apply when the blog is visible.',
                'affects' => 'Blog listing, categories, posts, home blog preview',
            ],
        ];
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, self::definitions());
    }

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::definitions());
    }
}
