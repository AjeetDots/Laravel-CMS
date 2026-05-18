<?php

namespace App\Models;

use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasSeo;
    use SoftDeletes;

    /** Slug reserved for the site About page (About Editorial template is only offered here). */
    public const ABOUT_SLUG = 'about';

    /** Slugs that may use the Contact layout template. */
    public const CONTACT_SLUGS = ['contact', 'contact-us'];

    /** Main HTML body appears before section blocks on the public page. */
    public const BODY_ORDER_CONTENT_FIRST = 'content_first';

    /** Section blocks appear before the main HTML body on the public page. */
    public const BODY_ORDER_SECTIONS_FIRST = 'sections_first';

    public const TEMPLATE_SIDEBAR = 'sidebar';

    /** Centred reading column; simpler layout without the full-width builder shell. */
    public const TEMPLATE_DEFAULT = 'default';

    public const TEMPLATE_FULL_WIDTH = 'full-width';

    /** Uses theme contact content + enquiry form (see `frontend.pages.contact`). */
    public const TEMPLATE_CONTACT = 'contact';

    /** Setting key for the template pre-selected when creating a new page. */
    public const SETTING_DEFAULT_TEMPLATE = 'page_default_template';

    protected $fillable = [
        'title',
        'slug',
        'hero_eyebrow',
        'hero_lede',
        'content',
        'body_order',
        'sidebar_content',
        'sidebar_cta_title',
        'sidebar_cta_text',
        'sidebar_cta_button_text',
        'meta_title',
        'meta_description',
        'template',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
        static::deleted(function (Page $page) {
            if ($page->isForceDeleting()) {
                return;
            }
            $page->sections()->get()->each->delete();
        });
    }

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('position');
    }

    public static function resolveSlugFromInput(?string $slug, string $title): string
    {
        $slug = $slug !== null ? trim($slug) : '';

        return $slug !== '' ? $slug : Str::slug($title);
    }

    /**
     * Templates with main content editor, optional sections, and content order in admin.
     *
     * @return list<string>
     */
    public static function sectionedTemplates(): array
    {
        return [self::TEMPLATE_DEFAULT, self::TEMPLATE_FULL_WIDTH, self::TEMPLATE_SIDEBAR];
    }

    /**
     * Layout templates that can be chosen as the site default for new pages.
     *
     * @return array<string, string> value => admin label
     */
    public static function defaultTemplateOptions(): array
    {
        return [
            self::TEMPLATE_DEFAULT => 'Default',
            self::TEMPLATE_FULL_WIDTH => 'Full width',
            self::TEMPLATE_SIDEBAR => 'With sidebar',
        ];
    }

    /** Template pre-selected in admin when creating a new page. */
    public static function defaultTemplate(): string
    {
        $value = Setting::get(self::SETTING_DEFAULT_TEMPLATE, self::TEMPLATE_FULL_WIDTH);
        $value = is_string($value) ? trim($value) : '';

        return array_key_exists($value, self::defaultTemplateOptions())
            ? $value
            : self::TEMPLATE_FULL_WIDTH;
    }

    /**
     * Templates allowed for a page slug (admin form + validation).
     *
     * @return list<string>
     */
    public static function allowedTemplatesForSlug(?string $slug): array
    {
        $slug = strtolower(trim((string) $slug));
        $templates = self::sectionedTemplates();

        if ($slug === self::ABOUT_SLUG) {
            $templates[] = 'about';
        }
        if (self::isContactSlug($slug)) {
            $templates[] = self::TEMPLATE_CONTACT;
        }

        return $templates;
    }

    public static function isContactSlug(?string $slug): bool
    {
        return in_array(strtolower(trim((string) $slug)), self::CONTACT_SLUGS, true);
    }

    /**
     * @return list<string>
     */
    public static function creatableTemplates(): array
    {
        return self::sectionedTemplates();
    }

    /**
     * CMS slugs that cannot be deleted (lowercase; empty string = legacy “home” row).
     *
     * @return list<string>
     */
    public static function protectedDeletionSlugs(): array
    {
        return [
            '',
            'home',
            'about',
            'terms-and-conditions',
            'privacy-policy',
            'cookie-policy',
            'portfolio',
            'finishes',
            'gallery',
            'services',
            'contact',
            'contact-us',
        ];
    }

    public function isDeletionProtected(): bool
    {
        $slug = strtolower(trim((string) ($this->slug ?? '')));

        return in_array($slug, static::protectedDeletionSlugs(), true);
    }

    public function resolvedSidebarCtaTitle(): string
    {
        return trim((string) ($this->sidebar_cta_title ?? ''));
    }

    public function resolvedSidebarCtaText(): string
    {
        return trim((string) ($this->sidebar_cta_text ?? ''));
    }

    public function resolvedSidebarCtaButtonText(): string
    {
        $text = trim((string) ($this->sidebar_cta_button_text ?? ''));

        return $text !== '' ? $text : 'Contact us';
    }
}
