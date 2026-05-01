<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model {
    protected $fillable = ['name', 'logo', 'website', 'sort_order', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
    public function getLogoUrlAttribute(): string {
        if (filter_var($this->logo, FILTER_VALIDATE_URL)) { return $this->logo; }
        return asset('storage/' . $this->logo);
    }
}
