<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGalleryRequest extends FormRequest
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
    }

    public function rules(): array
    {
        return [
            'title'      => 'nullable|string|max:200',
            'section_content' => 'nullable|string|max:2000',
            'image'      => ImageUploadRules::required(4096),
            'gallery_category_id' => ['nullable', Rule::exists('gallery_categories', 'id')->whereNull('deleted_at')],
            'sort_order' => ['integer', 'min:0', SortOrderRules::uniqueAmong('gallery_items', ['gallery_category_id' => 'gallery_category_id'])],
            'is_active'  => 'boolean',
        ];
    }
}
