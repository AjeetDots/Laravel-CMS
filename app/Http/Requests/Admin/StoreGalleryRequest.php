<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'nullable|string|max:200',
            'image'      => 'required|image|max:4096',
            'category'   => 'nullable|string|max:100',
            'sort_order' => 'integer',
            'is_active'  => 'boolean',
        ];
    }
}
