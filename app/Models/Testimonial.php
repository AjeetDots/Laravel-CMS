<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model {
    use SoftDeletes;
    protected $fillable = ['client_name', 'client_position', 'client_company', 'client_image', 'message', 'rating', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function getClientImageUrlAttribute(): ?string {
        if (!$this->client_image) { return null; }
        if (filter_var($this->client_image, FILTER_VALIDATE_URL)) { return $this->client_image; }
        return asset('storage/' . $this->client_image);
    }
}
