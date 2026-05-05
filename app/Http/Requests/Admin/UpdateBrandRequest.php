<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:100',
            'logo'       => ImageUploadRules::nullable(1024),
            'website'    => 'nullable|url|max:200',
            'sort_order' => 'integer',
            'is_active'  => 'boolean',
        ];
    }
}
