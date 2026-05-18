<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\ServiceFormLimits;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    use SortOrderValidationMessage;
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'slug.unique' => 'A service with this URL slug already exists. Use a different slug or title.',
            'title.max' => 'The title may not be longer than :max characters.',
            'short_description.max' => 'The short description may not be longer than :max characters (used on listing cards).',
            'description.max' => 'The full description is too long. Reduce content to at most :max characters (including HTML).',
            'seo.schema_markup.max' => 'Schema / JSON-LD may not exceed :max characters.',
        ]);
    }

    protected function prepareForValidation(): void
    {
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
        $id = $this->route('service')?->id;

        return array_merge([
            'title'             => 'required|string|max:'.ServiceFormLimits::TITLE_MAX,
            'slug'              => ['nullable', 'string', 'max:'.ServiceFormLimits::SLUG_MAX, Rule::unique('services', 'slug')->ignore($id)->whereNull('deleted_at')],
            'short_description' => 'required|string|max:'.ServiceFormLimits::SHORT_DESCRIPTION_MAX,
            'description'       => 'nullable|string|max:'.ServiceFormLimits::DESCRIPTION_MAX,
            'image'             => ImageUploadRules::nullable(2048),
            'hover_image'       => ImageUploadRules::nullable(2048),
            'hover_title'       => 'nullable|string|max:'.ServiceFormLimits::TITLE_MAX,
            'remove_hover_image'=> 'boolean',
            'icon'              => 'nullable|string|max:'.ServiceFormLimits::ICON_MAX,
            'badge'             => 'nullable|string|max:'.ServiceFormLimits::BADGE_MAX,
            'features'          => 'nullable|array',
            'features.*'        => 'nullable|string|max:'.ServiceFormLimits::FEATURE_LINE_MAX,
            'sort_order'        => ['integer', 'min:1', SortOrderRules::uniqueAmong('services', [], $this->route('service'))],
            'is_active'         => 'boolean',
            'finish_ids'        => 'nullable|array',
            'finish_ids.*'      => ['integer', Rule::exists('finishes', 'id')->whereNull('deleted_at')],
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
            'seo.schema_markup'       => 'nullable|string|max:65535',
        ];
    }
}
