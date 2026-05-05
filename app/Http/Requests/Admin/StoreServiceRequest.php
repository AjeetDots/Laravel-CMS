<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge([
            'title'             => 'required|string|max:200',
            'slug'              => 'nullable|string|unique:services,slug|max:200',
            'short_description' => 'required|string',
            'description'       => 'nullable|string',
            'image'             => ImageUploadRules::nullable(2048),
            'icon'              => 'nullable|string|max:100',
            'sort_order'        => 'integer',
            'is_active'         => 'boolean',
            'finish_ids'        => 'nullable|array',
            'finish_ids.*'      => 'integer|exists:finishes,id',
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
