<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model {
    protected $fillable = [
        'title',
        'title_line_2',
        'title_line_3',
        'title_line_4',
        'subtitle',
        'lead_text',
        'image',
        'button_text',
        'button_link',
        'sort_order',
        'panel',
        'is_active',
    ];

    /** When any extra line is set, the hero renders title as stacked lines (line 1 = title). */
    public function usesHeroTitleLines(): bool
    {
        return filled($this->title_line_2) || filled($this->title_line_3) || filled($this->title_line_4);
    }

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
