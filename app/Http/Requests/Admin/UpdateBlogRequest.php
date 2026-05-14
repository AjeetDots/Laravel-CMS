<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
{
    use SortOrderValidationMessage;

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'slug.unique' => 'A post with this URL slug already exists. Use a different slug or title.',
        ]);
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('category_id') === '' || $this->input('category_id') === false) {
            $this->merge(['category_id' => null]);
        }

        $title = trim((string) $this->input('title', ''));
        $slugInput = trim((string) $this->input('slug', ''));
        $slug = $slugInput !== '' ? $slugInput : ($title !== '' ? Str::slug($title) : '');
        if ($slug !== '') {
            $this->merge(['slug' => $slug]);
        }
    }

    public function rules(): array
    {
        $id = $this->route('blog')?->id;

        return array_merge([
            'title'        => 'required|string|max:255',
            'slug'         => ['nullable', 'string', 'max:255', Rule::unique('blog_posts', 'slug')->ignore($id)->whereNull('deleted_at')],
            'category'     => 'nullable|string|max:100',
            'category_id'  => ['nullable', Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'author'       => 'nullable|string|max:100',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'nullable|string',
            'image'        => ImageUploadRules::nullable(3072),
            'is_active'    => 'boolean',
            'published_at' => 'nullable|date',
            'sort_order'   => ['integer', 'min:0', SortOrderRules::uniqueAmong('blog_posts', ['category_id' => 'category_id'], $this->route('blog'))],
        ], $this->seoRules());
    }

    private function seoRules(): array
    {
        return [
            'seo.meta_title'          => 'nullable|string|max:70',
            'seo.meta_description'    => 'nullable|string|max:165',
            'seo.focus_keyword'       => 'nullable|string|max:100',
            'seo.canonical_url'       => 'nullable|url|max:500',
            'seo.robots_index'        => 'nullable|in:index,noindex',
            'seo.robots_follow'       => 'nullable|in:follow,nofollow',
            'seo.og_title'            => 'nullable|string|max:95',
            'seo.og_description'      => 'nullable|string|max:200',
            'seo.og_image'            => 'nullable|string|max:500',
            'seo.twitter_card'        => 'nullable|in:summary,summary_large_image',
            'seo.twitter_title'       => 'nullable|string|max:70',
            'seo.twitter_description' => 'nullable|string|max:200',
            'seo.twitter_image'       => 'nullable|string|max:500',
            'seo.schema_markup'       => 'nullable|string',
        ];
    }
}
