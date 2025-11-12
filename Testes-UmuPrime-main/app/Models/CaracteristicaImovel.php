<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model CaracteristicaImovel
 * 
 * Representa uma característica/amenidade de um imóvel
 * (Ex: piscina, churrasqueira, academia, etc)
 * 
 * @property int $id
 * @property int $imovel_id
 * @property string $caracteristica
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class CaracteristicaImovel extends Model
{
    use HasFactory;

    /**
     * Nome da tabela
     * 
     * @var string
     */
    protected $table = 'caracteristicas_imoveis';

    /**
     * Atributos atribuíveis em massa
     * 
     * @var array<string>
     */
    protected $fillable = [
        'imovel_id',
        'caracteristica',
    ];

    /**
     * Relacionamento: característica pertence a um imóvel
     * 
     * @return BelongsTo
     */
    public function imovel(): BelongsTo
    {
        return $this->belongsTo(Imovel::class);
    }
}
