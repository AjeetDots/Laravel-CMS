<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Admin\Concerns\SortOrderValidationMessage;
use App\Support\ImageUploadRules;
use App\Support\SortOrderRules;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
{
    use SortOrderValidationMessage;
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
            'sort_order'      => ['integer', 'min:0', SortOrderRules::uniqueAmong('testimonials', [], $this->route('testimonial'))],
            'is_active'       => 'boolean',
        ];
    }
}
