<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model SiteSetting
 * 
 * Representa as configurações gerais do site (singleton)
 * 
 * @property int $id
 * @property string|null $hero_image
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SiteSetting extends Model
{
    /**
     * Nome da tabela
     * 
     * @var string
     */
    protected $table = 'site_settings';

    /**
     * Atributos atribuíveis em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'hero_image',
        'updated_by',
    ];

    /**
     * Casts de atributos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'updated_by' => 'integer',
    ];

    /**
     * Retorna (ou cria) o único registro de configurações
     * 
     * @return self
     */
    public static function singleton(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'hero_image' => null,
                'updated_by' => 1,
            ]
        );
    }

    /**
     * Accessor: URL pública do banner hero com fallbacks
     * 
     * @return string
     */
    public function getHeroImageUrlAttribute(): string
    {
        // Se existe imagem customizada no storage
        if ($this->hero_image) {
            return Storage::url($this->hero_image);
        }

        // Fallback: imagem local padrão
        $defaultImagePath = public_path('images/banner-default.webp');
        if (file_exists($defaultImagePath)) {
            return asset('images/banner-default.webp');
        }

        // Fallback final: placeholder
        return 'https://placehold.co/1920x756/0066cc/ffffff?text=Banner+Home&font=poppins';
    }

    /**
     * Accessor: caminho do arquivo (compatibilidade com código legado)
     * 
     * @return string|null
     */
    public function getHeroImagePathAttribute(): ?string
    {
        return $this->hero_image;
    }
}
