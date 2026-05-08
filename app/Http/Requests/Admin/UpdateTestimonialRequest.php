<?php

namespace App\Http\Requests\Admin;

use App\Support\ImageUploadRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $testimonial = $this->route('testimonial');
        $hasExistingImage = $testimonial && !empty($testimonial->client_image);

        return [
            'client_name'     => 'required|string|max:100',
            'client_position' => 'nullable|string|max:100',
            'client_company'  => 'nullable|string|max:100',
            'client_image'    => $hasExistingImage
                ? ImageUploadRules::nullable(1024)
                : ImageUploadRules::required(1024),
            'message'         => 'required|string',
            'rating'          => 'integer|min:1|max:5',
            'sort_order'      => 'integer',
            'is_active'       => 'boolean',
        ];
    }
}
