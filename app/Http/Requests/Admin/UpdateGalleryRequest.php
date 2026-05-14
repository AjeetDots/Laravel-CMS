<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGalleryRequest extends FormRequest
{
    use SortOrderValidationMessage;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('gallery_category_id') === '' || $this->input('gallery_category_id') === false) {
            $this->merge(['gallery_category_id' => null]);
        }
        if ($this->has('title') && is_string($this->input('title'))) {
            $trimmed = trim($this->string('title')->toString());
            $this->merge(['title' => $trimmed === '' ? null : $trimmed]);
        }
    }

    public function rules(): array
    {
        return [
            'title'      => [
                'nullable',
                'string',
                'max:200',
                Rule::unique('gallery_items', 'title')
                    ->ignore($this->route('gallery'))
                    ->whereNull('deleted_at'),
            ],
            'section_content' => 'nullable|string|max:2000',
            'image'      => ImageUploadRules::nullable(4096),
            'gallery_category_id' => ['nullable', Rule::exists('gallery_categories', 'id')->whereNull('deleted_at')],
            'sort_order' => ['integer', 'min:0', SortOrderRules::uniqueAmong('gallery_items', ['gallery_category_id' => 'gallery_category_id'], $this->route('gallery'))],
            'is_active'  => 'boolean',
        ];
    }

    public function messages(): array
    {
        return array_merge($this->sortOrderValidationMessages(), [
            'title.unique' => 'Another gallery item already uses this title. Choose a different title, or leave the title blank.',
        ]);
    }
}
