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

    /** Main HTML body appears before section blocks on the public page. */
    public const BODY_ORDER_CONTENT_FIRST = 'content_first';

    /** Section blocks appear before the main HTML body on the public page. */
    public const BODY_ORDER_SECTIONS_FIRST = 'sections_first';

    public const TEMPLATE_SIDEBAR = 'sidebar';

    /** Uses theme contact content + enquiry form (see `frontend.pages.contact`). */
    public const TEMPLATE_CONTACT = 'contact';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'body_order',
        'sidebar_content',
        'sidebar_cta_title',
        'sidebar_cta_text',
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
     * Templates that use the stacked “Sections” builder in admin.
     *
     * @return list<string>
     */
    public static function sectionedTemplates(): array
    {
        return ['default', 'full-width', 'sidebar'];
    }

    /**
     * Templates allowed when creating a page (sections builder + contact layout).
     *
     * @return list<string>
     */
    public static function creatableTemplates(): array
    {
        return array_merge(self::sectionedTemplates(), [self::TEMPLATE_CONTACT]);
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
}
