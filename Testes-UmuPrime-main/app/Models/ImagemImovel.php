<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Model ImagemImovel
 * 
 * Representa uma imagem de um imóvel
 * 
 * @property int $id
 * @property int $imovel_id
 * @property string $caminho_imagem
 * @property string|null $legenda
 * @property int $ordem
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ImagemImovel extends Model
{
    use HasFactory;

    /**
     * Nome da tabela
     * 
     * @var string
     */
    protected $table = 'imagens_imoveis';

    /**
     * Atributos atribuíveis em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'imovel_id',
        'caminho_imagem',
        'legenda',
        'ordem',
    ];

    /**
     * Casts de atributos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'ordem' => 'integer',
    ];

    /**
     * Relacionamento: imagem pertence a um imóvel
     * 
     * @return BelongsTo
     */
    public function imovel(): BelongsTo
    {
        return $this->belongsTo(Imovel::class);
    }

    /**
     * Accessor: URL pública da imagem
     * 
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->caminho_imagem);
    }
}
