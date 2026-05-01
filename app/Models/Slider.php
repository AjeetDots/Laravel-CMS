<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model {
    protected $fillable = ['title', 'subtitle', 'image', 'button_text', 'button_link', 'sort_order', 'panel', 'is_active'];

    public static array $panelLabels = [
        'main'         => 'Center Main (cycles)',
        'right_top'    => 'Right Top Thumbnail',
        'right_bottom' => 'Right Bottom Thumbnail',
    ];
    protected $casts = ['is_active' => 'boolean'];
    public function getImageUrlAttribute(): string {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
}
