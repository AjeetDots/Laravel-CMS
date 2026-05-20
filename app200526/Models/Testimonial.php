<?php
namespace App\Models;
use App\Support\CmsImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model {
    use SoftDeletes;
    protected $fillable = ['client_name', 'client_position', 'client_company', 'client_image', 'message', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function listedSortOrder(): int
    {
        return max(1, (int) $this->sort_order);
    }

    public function getPlainMessageAttribute(): string
    {
        return trim(strip_tags(html_entity_decode((string) $this->message)));
    }

    public function getClientImageUrlAttribute(): string {
        return CmsImage::resolve($this->client_image);
    }
}
