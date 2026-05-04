<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailTemplate extends Model {
    protected $fillable = ['name', 'slug', 'subject', 'body', 'placeholders', 'is_active'];

    protected $casts = [
        'is_active'    => 'boolean',
        'placeholders' => 'array',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($t) {
            if (empty($t->slug)) {
                $t->slug = Str::slug($t->name);
            }
        });
    }

    public function render(array $vars): string {
        $body = $this->body;
        foreach ($vars as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }
        return $body;
    }
}
