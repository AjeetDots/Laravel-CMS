<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model {
    use SoftDeletes;
    protected $fillable = [
        'title',
        'subtitle',
        'lead_text',
        'image',
        'button_text',
        'button_link',
        'button2_text',
        'button2_link',
        'sort_order',
        'panel',
        'is_active',
    ];

    public static array $panelLabels = [
        'main'         => 'Center Main (cycles)',
        'right_top'    => 'Right Top Thumbnail',
        'right_bottom' => 'Right Bottom Thumbnail',
    ];
    protected $casts = ['is_active' => 'boolean'];

    public function listedSortOrder(): int
    {
        return max(1, (int) $this->sort_order);
    }

    public static function minimumRequiredCount(): int
    {
        return 1;
    }

    public static function canRemoveOne(): bool
    {
        return static::count() > static::minimumRequiredCount();
    }
    public function getImageUrlAttribute(): string {
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    /**
     * Whether the hero should render the headline as stacked lines (CSS modifier + carousel markup).
     */
    public function usesHeroTitleLines(): bool
    {
        return count($this->heroHeadlineLines()) > 1;
    }

    /**
     * Lines shown in the hero title area (max four for carousel data attributes / JS).
     *
     * @return list<string>
     */
    public function heroHeadlineLines(): array
    {
        if ($this->hasFilledExtraTitleLines()) {
            $lines = [trim((string) $this->title)];
            foreach (['title_line_2', 'title_line_3', 'title_line_4'] as $col) {
                if (isset($this->attributes[$col]) && filled($this->attributes[$col])) {
                    $lines[] = trim((string) $this->attributes[$col]);
                }
            }

            return $lines;
        }

        return $this->splitHeroTitleIntoLines((string) $this->title);
    }

    /**
     * Four strings for data-title / data-title-line-2..4 (see home page hero script).
     *
     * @return array{0: string, 1: string, 2: string, 3: string}
     */
    public function heroHeadlineDataSlots(): array
    {
        $lines = array_values(array_slice($this->heroHeadlineLines(), 0, 4));
        while (count($lines) < 4) {
            $lines[] = '';
        }

        return [$lines[0], $lines[1], $lines[2], $lines[3]];
    }

    protected function hasFilledExtraTitleLines(): bool
    {
        foreach (['title_line_2', 'title_line_3', 'title_line_4'] as $col) {
            if (isset($this->attributes[$col]) && filled($this->attributes[$col])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    protected function splitHeroTitleIntoLines(string $raw): array
    {
        if ($raw === '') {
            return [];
        }
        if (str_contains($raw, "\n")) {
            $parts = preg_split('/\r\n|\r|\n/', $raw) ?: [];
        } elseif (str_contains($raw, '|')) {
            $parts = explode('|', $raw);
        } else {
            return [trim($raw)];
        }
        $out = [];
        foreach ($parts as $part) {
            $part = trim((string) $part);
            if ($part !== '') {
                $out[] = $part;
            }
        }

        return $out !== [] ? $out : [trim($raw)];
    }
}
