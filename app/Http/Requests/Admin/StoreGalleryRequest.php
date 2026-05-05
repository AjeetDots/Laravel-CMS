<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
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
            'image'      => ImageUploadRules::required(4096),
            'gallery_category_id' => 'nullable|exists:gallery_categories,id',
            'sort_order' => 'integer',
            'is_active'  => 'boolean',
        ];
    }
}
