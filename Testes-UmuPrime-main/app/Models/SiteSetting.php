<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $table = 'site_settings';
    public $timestamps = true; // seu registro tem created_at/updated_at

    protected $fillable = [
        'hero_image',
        'updated_by',
    ];

    /** Retorna (ou cria) o único registro. */
    public static function singleton(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'hero_image' => null,
            'updated_by' => 1,
        ]);
    }

    /** URL pública do banner, com fallbacks. */
    public function getHeroImageUrlAttribute(): string
    {
        if ($this->hero_image) {
            // gera /storage/banners/arquivo.ext
            return Storage::url($this->hero_image);
        }

        // fallback local, se existir
        if (is_file(public_path('images/banner-default.webp'))) {
            return asset('images/banner-default.webp');
        }

        // último recurso (nunca quebra)
        return 'https://placehold.co/1920x756?text=Banner%20da%20Home&font=poppins';
    }

    /** Alias de compatibilidade para código antigo. */
    public function getHeroImagePathAttribute()
    {
        return $this->hero_image;
    }
}
