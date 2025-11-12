<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaracteristicaImovel extends Model
{
    use HasFactory;

    protected $table = 'caracteristicas_imoveis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'imovel_id',
        'caracteristica',
    ];

    // Se a tabela NÃO tiver created_at/updated_at:
    // public $timestamps = false;

    /**
     * Relacionamento: característica pertence a um imóvel
     */
    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
