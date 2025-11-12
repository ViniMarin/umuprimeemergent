<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemImovel extends Model
{
    use HasFactory;

    protected $table = 'imagens_imoveis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'imovel_id',
        'caminho_imagem',
        'legenda',
        'ordem',
    ];

    // Se a tabela NÃO tiver created_at/updated_at:
    // public $timestamps = false;

    /**
     * Relacionamento: imagem pertence a um imóvel
     */
    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
