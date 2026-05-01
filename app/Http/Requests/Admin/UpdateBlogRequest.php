<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('blog')?->id;

        return [
            'title'        => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:blog_posts,slug,' . $id,
            'category'     => 'nullable|string|max:100',
            'author'       => 'nullable|string|max:100',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'nullable|string',
            'image'        => 'nullable|image|max:3072',
            'is_active'    => 'boolean',
            'published_at' => 'nullable|date',
            'sort_order'   => 'integer',
        ];
    }
}
