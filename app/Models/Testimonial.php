<?php
namespace App\Models;
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

    public function getClientImageUrlAttribute(): ?string {
        if (!$this->client_image) { return null; }
        if (filter_var($this->client_image, FILTER_VALIDATE_URL)) { return $this->client_image; }
        return asset('storage/' . $this->client_image);
    }
}
