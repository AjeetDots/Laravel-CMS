<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGalleryCategoryRequest extends FormRequest
{
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
                Rule::unique('gallery_categories', 'slug')->ignore($category),
            ],
            'sort_order' => 'integer',
        ];
    }
}
