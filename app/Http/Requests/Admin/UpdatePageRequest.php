<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\ValidatesPageSectionImages;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
{
    use ValidatesPageSectionImages;

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'A page with this URL slug already exists. Change the slug or title.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $title = trim((string) $this->input('title', ''));
        $slugInput = $this->input('slug');
        $resolved = Page::resolveSlugFromInput(
            is_string($slugInput) ? $slugInput : null,
            $title
        );
        if ($resolved !== '') {
            $this->merge(['slug' => $resolved]);
        }

        $page = $this->route('page');
        if ($page instanceof Page && $page->hasFixedTemplate()) {
            $this->merge(['template' => $page->template]);
        }
    }

    public function rules(): array
    {
        $id = $this->route('page')?->id;

        $resolvedSlug = Page::resolveSlugFromInput(
            $this->input('slug'),
            (string) $this->input('title', '')
        );
        $allowedTemplates = Page::allowedTemplatesForSlug($resolvedSlug);

        $page = $this->route('page');
        if ($page instanceof Page && $page->hasFixedTemplate()) {
            $allowedTemplates = array_unique(array_merge($allowedTemplates, [$page->template]));
        }

        return array_merge([
            'title' => 'required|string|max:200',
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('pages', 'slug')->ignore($id)->whereNull('deleted_at')],
            'hero_eyebrow' => 'nullable|string|max:120',
            'hero_lede' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'body_order' => ['nullable', 'string', Rule::in([Page::BODY_ORDER_CONTENT_FIRST, Page::BODY_ORDER_SECTIONS_FIRST])],
            'sidebar_content' => 'nullable|string',
            'sidebar_cta_title' => 'nullable|string|max:200',
            'sidebar_cta_text' => 'nullable|string|max:600',
            'sidebar_cta_button_text' => 'nullable|string|max:120',
            'meta_title' => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'template' => ['required', 'string', Rule::in($allowedTemplates)],
            'is_active' => 'boolean',
        ], $this->seoRules());
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $this->validatePageSectionImages($validator);

            $page = $this->route('page');
            if (! $page instanceof Page || ! $page->hasFixedTemplate()) {
                return;
            }

            $slug = Page::resolveSlugFromInput(
                $this->input('slug'),
                (string) $this->input('title', '')
            );

            if ($page->template === Page::TEMPLATE_CONTACT && ! Page::isContactSlug($slug)) {
                $validator->errors()->add(
                    'slug',
                    'Contact pages must use the contact or contact-us URL slug.'
                );
            }

            if ($page->template === Page::TEMPLATE_ABOUT && ! Page::isAboutSlug($slug)) {
                $validator->errors()->add(
                    'slug',
                    'About pages must use the about or about-us URL slug.'
                );
            }
        });
    }

    private function seoRules(): array
    {
        return [
            'seo.meta_title' => 'nullable|string|max:70',
            'seo.meta_description' => 'nullable|string|max:165',
            'seo.focus_keyword' => 'nullable|string|max:100',
            'seo.canonical_url' => 'nullable|url|max:500',
            'seo.robots_index' => 'nullable|in:index,noindex',
            'seo.robots_follow' => 'nullable|in:follow,nofollow',
            'seo.og_title' => 'nullable|string|max:95',
            'seo.og_description' => 'nullable|string|max:200',
            'seo.og_image' => 'nullable|string|max:500',
            'seo.twitter_card' => 'nullable|in:summary,summary_large_image',
            'seo.twitter_title' => 'nullable|string|max:70',
            'seo.twitter_description' => 'nullable|string|max:200',
            'seo.twitter_image' => 'nullable|string|max:500',
            'seo.schema_markup' => 'nullable|string',
        ];
    }
}
