<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('service')?->id;

        return [
            'title'             => 'required|string|max:200',
            'slug'              => 'nullable|string|unique:services,slug,' . $id . '|max:200',
            'short_description' => 'required|string',
            'description'       => 'nullable|string',
            'image'             => 'nullable|image|max:2048',
            'icon'              => 'nullable|string|max:100',
            'sort_order'        => 'integer',
            'is_active'         => 'boolean',
        ];
    }
}
