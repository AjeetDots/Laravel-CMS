<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('blog')?->id;

        return array_merge([
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:blog_posts,slug,' . $id,
            'category'     => 'nullable|string|max:100',
            'category_id'  => 'nullable|exists:categories,id',
            'author'       => 'nullable|string|max:100',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'nullable|string',
            'image'        => ImageUploadRules::nullable(3072),
            'is_active'    => 'boolean',
            'published_at' => 'nullable|date',
            'sort_order'   => 'integer',
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
