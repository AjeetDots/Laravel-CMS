<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePortfolioRequest extends FormRequest
{
    use SortOrderValidationMessage;

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'slug.unique' => 'A project with this URL slug already exists. Use a different title, set a custom slug, or edit the existing project.',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('sort_order') && $this->input('sort_order') === '') {
            $this->merge(['sort_order' => null]);
        }

        $title = trim((string) $this->input('title', ''));
        $slugInput = trim((string) $this->input('slug', ''));
        $slug = $slugInput !== ''
            ? Str::slug($slugInput)
            : ($title !== '' ? Str::slug($title) : '');
        if ($slug !== '') {
            $this->merge(['slug' => $slug]);
        }
    }

    public function rules(): array
    {
        return array_merge([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('portfolios', 'slug')->whereNull('deleted_at')],
            'description' => 'nullable|string',
            'project_type' => ['required', 'string', Rule::in(['reference', 'real'])],
            'cover_image' => ImageUploadRules::nullable(5120),
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => ImageUploadRules::nullable(5120),
            'tags_raw' => 'nullable|string',
            'sort_order' => ['nullable', 'integer', 'min:0', SortOrderRules::uniqueAmong('portfolios', [])],
            'is_active' => 'nullable|boolean',
        ], $this->seoRules());
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
            'seo.schema_markup' => 'nullable|string|max:65535',
        ];
    }
}
