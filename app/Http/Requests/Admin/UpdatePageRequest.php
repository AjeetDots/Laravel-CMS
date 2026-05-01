<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('page')?->id;

        return [
            'title'            => 'required|string|max:200',
            'slug'             => 'nullable|string|unique:pages,slug,' . $id . '|max:200',
            'content'          => 'nullable|string',
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'template'         => 'required|string',
            'is_active'        => 'boolean',
        ];
    }
}
