<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryCategoryRequest extends FormRequest
{
    use SortOrderValidationMessage;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:120',
            'slug'       => 'nullable|string|max:160',
            'sort_order' => ['integer', 'min:1', SortOrderRules::uniqueAmong('gallery_categories', [])],
        ];
    }
}
