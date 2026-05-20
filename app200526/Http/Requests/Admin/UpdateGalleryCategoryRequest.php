<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGalleryCategoryRequest extends FormRequest
{
    use SortOrderValidationMessage;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('gallery_category');

        return [
            'name'       => 'required|string|max:120',
            'slug'       => [
                'nullable',
                'string',
                'max:160',
                Rule::unique('gallery_categories', 'slug')->ignore($category)->whereNull('deleted_at'),
            ],
            'sort_order' => ['integer', 'min:1', SortOrderRules::uniqueAmong('gallery_categories', [], $category)],
        ];
    }
}
