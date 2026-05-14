<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreTestimonialRequest extends FormRequest
{
    use SortOrderValidationMessage;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name'     => 'required|string|max:100',
            'client_position' => 'nullable|string|max:100',
            'client_company'  => 'nullable|string|max:100',
            'client_image'    => ImageUploadRules::required(1024),
            'message'         => 'required|string',
            'rating'          => 'integer|min:1|max:5',
            'sort_order'      => ['integer', 'min:0', SortOrderRules::uniqueAmong('testimonials', [])],
            'is_active'       => 'boolean',
        ];
    }
}
