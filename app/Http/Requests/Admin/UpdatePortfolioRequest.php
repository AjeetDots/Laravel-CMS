<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        $id = $this->route('portfolio')?->id;
        return [
            'title'          => 'required|string|max:255',
            'slug'           => "nullable|string|max:255|unique:portfolios,slug,{$id}",
            'description'    => 'nullable|string',
            'project_type'   => 'required|in:reference,real',
            'cover_image'    => 'nullable|image|max:4096',
            'gallery_images' => 'nullable|array',
            'gallery_images.*'=> 'image|max:4096',
            'clear_gallery'  => 'nullable|boolean',
            'tags_raw'       => 'nullable|string',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'nullable|boolean',
        ];
    }
}
