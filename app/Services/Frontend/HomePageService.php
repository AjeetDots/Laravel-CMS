<?php

namespace App\Services\Frontend;

use App\Contracts\Frontend\HomePageServiceInterface;
use App\Models\BlogPost;
use App\Models\Brand;
use App\Models\Finish;
use App\Models\GalleryItem;
use App\Models\HomePageSection;
use App\Models\PhoneCountry;
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
        $whySection = $this->getWhySectionData($pick('why'));
        $processSection = $this->getProcessSectionData($pick('process'));
        $commissionsSection = $this->getCommissionsSectionData($pick('commissions'));
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
            'gallery' => GalleryItem::query()
                ->with('galleryCategory')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'atelierSection' => $atelierSection,
            'finishesSection' => $finishesSection,
            'servicesSection' => $servicesSection,
            'whySection' => $whySection,
            'processSection' => $processSection,
            'commissionsSection' => $commissionsSection,
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
        $buttonUrl = isset($data['button_url']) ? trim((string) $data['button_url']) : '';

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'heading_line_1' => isset($data['heading_line_1']) ? trim((string) $data['heading_line_1']) : '',
            'heading_line_2' => isset($data['heading_line_2']) ? trim((string) $data['heading_line_2']) : '',
            'card_label' => isset($data['card_label']) ? trim((string) $data['card_label']) : '',
            'button_text' => isset($data['button_text']) ? trim((string) $data['button_text']) : '',
            'button_url' => $buttonUrl !== '' ? $buttonUrl : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getServicesSectionData(array $data): array
    {
        $buttonUrl = isset($data['button_url']) ? trim((string) $data['button_url']) : '';

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'heading_line_1' => isset($data['heading_line_1']) ? trim((string) $data['heading_line_1']) : '',
            'heading_line_2' => isset($data['heading_line_2']) ? trim((string) $data['heading_line_2']) : '',
            'button_text' => isset($data['button_text']) ? trim((string) $data['button_text']) : '',
            'button_url' => $buttonUrl !== '' ? $buttonUrl : null,
            'card_link_text' => isset($data['card_link_text']) ? trim((string) $data['card_link_text']) : '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getWhySectionData(array $data): array
    {
        $cards = is_array($data['cards'] ?? null) ? $data['cards'] : [];
        $resolvedCards = [];
        foreach ($cards as $card) {
            if (! is_array($card)) {
                continue;
            }
            $resolvedCards[] = [
                'icon' => isset($card['icon']) ? trim((string) $card['icon']) : '',
                'title' => isset($card['title']) ? trim((string) $card['title']) : '',
                'desc' => isset($card['desc']) ? trim((string) $card['desc']) : '',
            ];
        }

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'heading' => isset($data['heading']) ? trim((string) $data['heading']) : '',
            'lead' => isset($data['lead']) ? trim((string) $data['lead']) : '',
            'cards' => $resolvedCards,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getProcessSectionData(array $data): array
    {
        $steps = is_array($data['steps'] ?? null) ? $data['steps'] : [];
        $resolvedSteps = [];
        foreach ($steps as $step) {
            if (! is_array($step)) {
                continue;
            }
            $resolvedSteps[] = [
                'num' => isset($step['num']) ? trim((string) $step['num']) : '',
                'title' => isset($step['title']) ? trim((string) $step['title']) : '',
                'desc' => isset($step['desc']) ? trim((string) $step['desc']) : '',
            ];
        }

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'heading_line_1' => isset($data['heading_line_1']) ? trim((string) $data['heading_line_1']) : '',
            'heading_line_2' => isset($data['heading_line_2']) ? trim((string) $data['heading_line_2']) : '',
            'steps' => $resolvedSteps,
        ];
    }

    /**
     * Selected work grid (active Gallery module items).
     *
     * @return array<string, mixed>
     */
    private function getCommissionsSectionData(array $data): array
    {
        $buttonUrl = isset($data['button_url']) ? trim((string) $data['button_url']) : '';

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'heading_line_1' => isset($data['heading_line_1']) ? trim((string) $data['heading_line_1']) : '',
            'button_text' => isset($data['button_text']) ? trim((string) $data['button_text']) : '',
            'button_url' => $buttonUrl !== '' ? $buttonUrl : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getBeginCtaSectionData(array $data): array
    {
        $primaryUrl = isset($data['primary_btn_url']) ? trim((string) $data['primary_btn_url']) : '';
        $secondaryUrl = isset($data['secondary_btn_url']) ? trim((string) $data['secondary_btn_url']) : '';

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'title_line_1' => isset($data['title_line_1']) ? trim((string) $data['title_line_1']) : '',
            'title_line_2' => isset($data['title_line_2']) ? trim((string) $data['title_line_2']) : '',
            'primary_btn_text' => isset($data['primary_btn_text']) ? trim((string) $data['primary_btn_text']) : '',
            'primary_btn_url' => $primaryUrl !== '' ? $primaryUrl : null,
            'secondary_btn_text' => isset($data['secondary_btn_text']) ? trim((string) $data['secondary_btn_text']) : '',
            'secondary_btn_url' => $secondaryUrl !== '' ? $secondaryUrl : null,
            'bg_image' => isset($data['bg_image']) && $data['bg_image'] !== '' ? $data['bg_image'] : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getContactBandSectionData(array $data): array
    {
        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'heading' => isset($data['heading']) ? trim((string) $data['heading']) : '',
            'panel_title' => isset($data['panel_title']) ? trim((string) $data['panel_title']) : '',
            'name_placeholder' => isset($data['name_placeholder']) ? trim((string) $data['name_placeholder']) : '',
            'email_placeholder' => isset($data['email_placeholder']) ? trim((string) $data['email_placeholder']) : '',
            'phone_placeholder' => isset($data['phone_placeholder']) ? trim((string) $data['phone_placeholder']) : '',
            'message_placeholder' => isset($data['message_placeholder']) ? trim((string) $data['message_placeholder']) : '',
            'submit_text' => isset($data['submit_text']) ? trim((string) $data['submit_text']) : '',
            'visual_image' => isset($data['visual_image']) && $data['visual_image'] !== '' ? $data['visual_image'] : null,
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
            'kicker' => isset($data['kicker']) ? trim((string) $data['kicker']) : '',
            'title_line_1' => isset($data['title_line_1']) ? trim((string) $data['title_line_1']) : '',
            'title_line_2' => isset($data['title_line_2']) ? trim((string) $data['title_line_2']) : '',
            'marquee_segments' => $marqueeSegments,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getBlogPreviewSectionData(array $data): array
    {
        $buttonUrl = isset($data['button_url']) ? trim((string) $data['button_url']) : '';

        return [
            'is_enabled' => array_key_exists('is_enabled', $data) ? ! empty($data['is_enabled']) : true,
            'eyebrow' => isset($data['eyebrow']) ? trim((string) $data['eyebrow']) : '',
            'heading' => isset($data['heading']) ? trim((string) $data['heading']) : '',
            'button_text' => isset($data['button_text']) ? trim((string) $data['button_text']) : '',
            'button_url' => $buttonUrl !== '' ? $buttonUrl : null,
            'read_more_text' => isset($data['read_more_text']) ? trim((string) $data['read_more_text']) : '',
        ];
    }
}
