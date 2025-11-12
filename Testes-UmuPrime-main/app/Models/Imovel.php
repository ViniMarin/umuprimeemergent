<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * Model Imovel
 * 
 * Representa um imóvel para venda ou aluguel
 * 
 * @property int $id
 * @property string $referencia
 * @property string $titulo
 * @property string|null $descricao
 * @property string $tipo_negocio
 * @property string $tipo_imovel
 * @property float $valor
 * @property float|null $valor_condominio
 * @property float|null $valor_iptu
 * @property string $endereco
 * @property string|null $numero
 * @property string|null $complemento
 * @property string $bairro
 * @property string $cidade
 * @property string $estado
 * @property string|null $cep
 * @property float|null $area_total
 * @property float|null $area_construida
 * @property int|null $quartos
 * @property int|null $banheiros
 * @property int|null $vagas_garagem
 * @property int|null $suites
 * @property int|null $andar
 * @property bool $mobiliado
 * @property string $status
 * @property bool $destaque
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Imovel extends Model
{
    use HasFactory;

    /**
     * Nome da tabela
     * 
     * @var string
     */
    protected $table = 'imoveis';

    /**
     * Atributos atribuíveis em massa
     * 
     * @var array<string>
     */
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
        'longitude',
    ];

    /**
     * Casts de atributos
     * 
     * @var array<string, string>
     */
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

    /**
     * Relacionamento: um imóvel tem muitas imagens
     * 
     * @return HasMany
     */
    public function imagens(): HasMany
    {
        return $this->hasMany(ImagemImovel::class);
    }

    /**
     * Relacionamento: um imóvel tem muitas características
     * 
     * @return HasMany
     */
    public function caracteristicas(): HasMany
    {
        return $this->hasMany(CaracteristicaImovel::class);
    }

    /**
     * Accessor: valor formatado em Real brasileiro
     * 
     * @return string
     */
    public function getValorFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    /**
     * Accessor: primeira imagem do imóvel
     * 
     * @return ImagemImovel|null
     */
    public function getPrimeiraImagemAttribute(): ?ImagemImovel
    {
        return $this->imagens()->orderBy('ordem')->first();
    }

    /**
     * Accessor: endereço completo formatado
     * 
     * @return string
     */
    public function getEnderecoCompletoAttribute(): string
    {
        $parts = array_filter([
            $this->endereco,
            $this->numero,
            $this->complemento,
            $this->bairro,
            $this->cidade,
            $this->estado,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Scope: apenas imóveis disponíveis
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisponiveis($query)
    {
        return $query->where('status', 'disponivel');
    }

    /**
     * Scope: apenas imóveis em destaque
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDestaque($query)
    {
        return $query->where('destaque', true);
    }

    /**
     * Scope: filtrar por tipo de negócio
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tipo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTipoNegocio($query, string $tipo)
    {
        return $query->where('tipo_negocio', $tipo);
    }

    /**
     * Event: ao deletar um imóvel, deleta suas imagens do storage
     * 
     * @return void
     */
    protected static function booted(): void
    {
        static::deleting(function (Imovel $imovel) {
            foreach ($imovel->imagens as $imagem) {
                if ($imagem->caminho_imagem && Storage::disk('public')->exists($imagem->caminho_imagem)) {
                    Storage::disk('public')->delete($imagem->caminho_imagem);
                }
                $imagem->delete();
            }

            // Deleta características associadas
            $imovel->caracteristicas()->delete();
        });
    }
}
