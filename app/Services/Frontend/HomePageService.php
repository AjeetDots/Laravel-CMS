<?php

namespace App\Services\Frontend;

use App\Contracts\Frontend\HomePageServiceInterface;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Finish;
use App\Models\GalleryItem;
use App\Models\HomePageSection;
use App\Models\PhoneCountry;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Testimonial;

class HomePageService implements HomePageServiceInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getPageData(): array
    {
        $sections = HomePageSection::query()
            ->get(['section_key', 'data'])
            ->keyBy('section_key');

        $pick = function (string $key) use ($sections): array {
            $raw = $sections->get($key)?->data;

            return is_array($raw) ? $raw : [];
        };

        $atelierSection = $this->getAtelierSectionData($pick('atelier'));
        $finishesSection = $this->getFinishesSectionData($pick('finishes'));
        $servicesSection = $this->getServicesSectionData($pick('services'));
        $commissionsSection = $this->getCommissionsSectionData($pick('commissions'));
        $whySection = $this->getWhySectionData($pick('why'));
        $processSection = $this->getProcessSectionData($pick('process'));
        $beginCtaSection = $this->getBeginCtaSectionData($pick('begin_cta'));
        $contactBandSection = $this->getContactBandSectionData($pick('contact_band'));
        $brandsStripSection = $this->getBrandsStripSectionData($pick('brands_strip'));
        $blogPreviewSection = $this->getBlogPreviewSectionData($pick('blog_preview'));

        return [
            'sliders' => Slider::query()
                ->where('is_active', true)
                ->where('panel', 'main')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'sliderRight1' => Slider::query()
                ->where('is_active', true)
                ->where('panel', 'right_top')
                ->first(),
            'sliderRight2' => Slider::query()
                ->where('is_active', true)
                ->where('panel', 'right_bottom')
                ->first(),
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(6)
                ->get(),
            'finishes' => Finish::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->limit(6)
                ->get(),
            'portfolios' => Portfolio::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(4)
                ->get(),
            'gallery' => GalleryItem::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(8)
                ->get(),
            'testimonials' => Testimonial::query()
                ->where('is_active', true)
                ->whereNotNull('message')
                ->where('message', '!=', '')
                ->orderBy('sort_order')
                ->get(),
            'brands' => Brand::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'blogPosts' => BlogPost::query()
                ->where('is_active', true)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
            'atelierSection' => $atelierSection,
            'finishesSection' => $finishesSection,
            'servicesSection' => $servicesSection,
            'commissionsSection' => $commissionsSection,
            'whySection' => $whySection,
            'processSection' => $processSection,
            'beginCtaSection' => $beginCtaSection,
            'contactBandSection' => $contactBandSection,
            'brandsStripSection' => $brandsStripSection,
            'blogPreviewSection' => $blogPreviewSection,
            'phoneCountries' => PhoneCountry::listingQuery()->get(['id', 'iso_code', 'name', 'dial_code', 'flag_emoji']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getAtelierSectionData(array $data): array
    {
        return [
            'is_enabled' => ! empty($data['is_enabled']),
            'kicker' => $data['kicker'] ?? null,
            'heading_line_1' => $data['heading_line_1'] ?? null,
            'heading_line_2' => $data['heading_line_2'] ?? null,
            'heading_line_3' => $data['heading_line_3'] ?? null,
            'body' => $data['body'] ?? null,
            'cta_text' => $data['cta_text'] ?? null,
            'cta_url' => $data['cta_url'] ?? null,
            'booking_label' => $data['booking_label'] ?? null,
            'booking_text' => $data['booking_text'] ?? null,
            'booking_url' => $data['booking_url'] ?? null,
            'primary_image' => $data['primary_image'] ?? null,
            'secondary_image' => $data['secondary_image'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getFinishesSectionData(array $data): array
    {
        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'The Finishes',
            'heading_line_1' => $data['heading_line_1'] ?? 'Six surfaces,',
            'heading_line_2' => $data['heading_line_2'] ?? 'infinite tones.',
            'card_label' => $data['card_label'] ?? 'Finish',
            'button_text' => $data['button_text'] ?? 'All finishes',
            'button_url' => $data['button_url'] ?? route('finishes'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getServicesSectionData(array $data): array
    {
        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'Our Services',
            'heading_line_1' => $data['heading_line_1'] ?? 'Three disciplines,',
            'heading_line_2' => $data['heading_line_2'] ?? 'one obsession.',
            'button_text' => $data['button_text'] ?? 'See all services',
            'button_url' => $data['button_url'] ?? route('services'),
            'card_link_text' => $data['card_link_text'] ?? 'Discover',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getCommissionsSectionData(array $data): array
    {
        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'Selected Work',
            'heading_line_1' => $data['heading_line_1'] ?? 'Recent commissions.',
            'button_text' => $data['button_text'] ?? 'View full gallery',
            'button_url' => $data['button_url'] ?? route('gallery'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getWhySectionData(array $data): array
    {
        $defaults = [
            ['icon' => 'fa-award', 'title' => 'Master Craftsmanship', 'desc' => 'Every surface mixed, applied and polished by hand.'],
            ['icon' => 'fa-palette', 'title' => 'Bespoke by Design', 'desc' => 'Custom tones, textures and profiles, never off-the-shelf.'],
            ['icon' => 'fa-clapperboard', 'title' => 'Trusted by Productions', 'desc' => 'Selected for major film, TV and editorial productions.'],
            ['icon' => 'fa-leaf', 'title' => 'Considered Materials', 'desc' => 'Lime-based, breathable, low-VOC formulations.'],
        ];
        $cards = is_array($data['cards'] ?? null) ? $data['cards'] : [];
        $resolvedCards = [];
        for ($i = 0; $i < 4; $i++) {
            $resolvedCards[] = [
                'icon' => $cards[$i]['icon'] ?? $defaults[$i]['icon'],
                'title' => $cards[$i]['title'] ?? $defaults[$i]['title'],
                'desc' => $cards[$i]['desc'] ?? $defaults[$i]['desc'],
            ];
        }

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'Why Bespoke Ornate',
            'heading' => $data['heading'] ?? 'A studio defined by its hands.',
            'lead' => $data['lead'] ?? 'Each project is led by master artisans trained in traditional Italian techniques and refined for the demands of contemporary architecture.',
            'cards' => $resolvedCards,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getProcessSectionData(array $data): array
    {
        $defaults = [
            ['num' => '01', 'title' => 'Consultation', 'desc' => 'We visit your space, listen and study the light.'],
            ['num' => '02', 'title' => 'Design', 'desc' => 'Bespoke samples, tones and textures developed in studio.'],
            ['num' => '03', 'title' => 'Quote', 'desc' => 'A clear, transparent proposal with timelines.'],
            ['num' => '04', 'title' => 'Execution', 'desc' => 'Hand-applied by our master artisans on site.'],
        ];
        $steps = is_array($data['steps'] ?? null) ? $data['steps'] : [];
        $resolvedSteps = [];
        for ($i = 0; $i < 4; $i++) {
            $resolvedSteps[] = [
                'num' => $steps[$i]['num'] ?? $defaults[$i]['num'],
                'title' => $steps[$i]['title'] ?? $defaults[$i]['title'],
                'desc' => $steps[$i]['desc'] ?? $defaults[$i]['desc'],
            ];
        }

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'Our Process',
            'heading_line_1' => $data['heading_line_1'] ?? 'From first conversation',
            'heading_line_2' => $data['heading_line_2'] ?? 'to final polish.',
            'steps' => $resolvedSteps,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getBeginCtaSectionData(array $data): array
    {
        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'Begin a Project',
            'title_line_1' => $data['title_line_1'] ?? 'Transform your space',
            'title_line_2' => $data['title_line_2'] ?? 'into a quiet masterpiece.',
            'primary_btn_text' => $data['primary_btn_text'] ?? 'Get free consultation',
            'primary_btn_url' => $data['primary_btn_url'] ?? route('contact'),
            'secondary_btn_text' => $data['secondary_btn_text'] ?? 'Call the studio',
            'secondary_btn_url' => $data['secondary_btn_url'] ?? null,
            'bg_image' => $data['bg_image'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getContactBandSectionData(array $data): array
    {
        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'Contact Us',
            'heading' => $data['heading'] ?? 'How we can help?',
            'panel_title' => $data['panel_title'] ?? 'Contact Us',
            'name_placeholder' => $data['name_placeholder'] ?? 'Your Name',
            'email_placeholder' => $data['email_placeholder'] ?? 'Email',
            'phone_placeholder' => $data['phone_placeholder'] ?? 'Phone(Optional)',
            'message_placeholder' => $data['message_placeholder'] ?? 'Tell us about your space',
            'submit_text' => $data['submit_text'] ?? 'Send Enquiry',
            'subject' => $data['subject'] ?? 'Website enquiry (home)',
            'visual_image' => $data['visual_image'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getBrandsStripSectionData(array $data): array
    {
        $marqueeSegments = isset($data['marquee_segments']) ? (int) $data['marquee_segments'] : 8;
        if ($marqueeSegments < 1 || $marqueeSegments > 20) {
            $marqueeSegments = 8;
        }

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'kicker' => $data['kicker'] ?? 'Partners & collaborators',
            'title_line_1' => $data['title_line_1'] ?? 'Trusted by',
            'title_line_2' => $data['title_line_2'] ?? 'leading names',
            'marquee_segments' => $marqueeSegments,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getBlogPreviewSectionData(array $data): array
    {
        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => $data['eyebrow'] ?? 'Our Blog',
            'heading' => $data['heading'] ?? 'Latest News',
            'button_text' => $data['button_text'] ?? 'All Blogs',
            'button_url' => $data['button_url'] ?? route('blog.index'),
            'read_more_text' => $data['read_more_text'] ?? 'Read More',
        ];
    }
}
