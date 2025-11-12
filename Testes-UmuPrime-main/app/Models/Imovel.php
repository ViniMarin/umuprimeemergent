<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Imovel extends Model
{
    use HasFactory;

    protected $table = 'imoveis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'referencia', 
        'titulo', 
        'descricao', 
        'tipo_negocio', 
        'tipo_imovel',
        'valor', 
        'valor_condominio', 
        'valor_iptu', 
        'endereco', 
        'numero',
        'complemento', 
        'bairro', 
        'cidade', 
        'estado', 
        'cep', 
        'area_total',
        'area_construida', 
        'quartos', 
        'banheiros', 
        'vagas_garagem', 
        'suites',
        'andar',
        'mobiliado', 
        'status', 
        'destaque', 
        'latitude', 
        'longitude'
    ];

    protected $casts = [
        'mobiliado' => 'boolean',
        'destaque' => 'boolean',
        'valor' => 'decimal:2',
        'valor_condominio' => 'decimal:2',
        'valor_iptu' => 'decimal:2',
        'area_total' => 'decimal:2',
        'area_construida' => 'decimal:2',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // 🔗 Relacionamentos
    public function imagens()
    {
        return $this->hasMany(ImagemImovel::class);
    }

    public function caracteristicas()
    {
        return $this->hasMany(CaracteristicaImovel::class);
    }

    // 💰 Accessors
    public function getValorFormatadoAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    public function getPrimeiraImagemAttribute()
    {
        return $this->imagens()->orderBy('ordem')->first();
    }

    // 🔥 Quando deletar um imóvel, deleta imagens do banco + storage
    protected static function booted()
    {
        static::deleting(function ($imovel) {
            foreach ($imovel->imagens as $img) {
                if ($img->caminho_imagem && Storage::disk('public')->exists($img->caminho_imagem)) {
                    Storage::disk('public')->delete($img->caminho_imagem);
                }
                $img->delete();
            }
        });
    }
}
