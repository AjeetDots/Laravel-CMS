<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|unique:pages,slug|max:200',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'template'         => 'required|string',
            'is_active'        => 'boolean',
        ];
    }
}
