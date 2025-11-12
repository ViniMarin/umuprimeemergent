<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Controller ImovelController
 * 
 * Gerencia as páginas públicas de imóveis
 */
class ImovelController extends Controller
{
    /**
     * Tipos de imóveis permitidos para filtros
     * 
     * @var array<string>
     */
    private const TIPOS_PERMITIDOS = [
        'apartamento',
        'casa',
        'terreno',
        'sala_comercial',
        'salao_comercial',
        'chacara',
        'sobrado',
    ];

    /**
     * Exibe detalhes de um imóvel específico
     * 
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        // Carrega imóvel com relacionamentos
        $imovel = Imovel::with([
            'imagens' => fn ($q) => $q->orderBy('ordem'),
            'caracteristicas',
        ])->findOrFail($id);

        // Busca imóveis relacionados (mesmo tipo de negócio e cidade)
        $imoveisRelacionados = Imovel::query()
            ->disponivel()
            ->with(['imagens' => fn ($q) => $q->orderBy('ordem')])
            ->tipoNegocio($imovel->tipo_negocio)
            ->where('cidade', $imovel->cidade)
            ->where('id', '!=', $imovel->id)
            ->limit(4)
            ->get();

        return view('imovel.show', compact('imovel', 'imoveisRelacionados'));
    }

    /**
     * Lista imóveis para aluguel
     * 
     * @param Request $request
     * @return View
     */
    public function aluguel(Request $request): View
    {
        return $this->listarPorTipo('aluguel', $request);
    }

    /**
     * Lista imóveis para venda
     * 
     * @param Request $request
     * @return View
     */
    public function venda(Request $request): View
    {
        return $this->listarPorTipo('venda', $request);
    }

    /**
     * Lista imóveis por tipo de negócio com filtros
     * 
     * @param string $tipo
     * @param Request $request
     * @return View
     */
    private function listarPorTipo(string $tipo, Request $request): View
    {
        // Query base: apenas disponíveis do tipo desejado
        $query = Imovel::query()
            ->disponivel()
            ->tipoNegocio($tipo)
            ->with(['imagens' => fn ($q) => $q->orderBy('ordem')]);

        // Cidades disponíveis para o filtro
        $cidades = Imovel::query()
            ->disponivel()
            ->tipoNegocio($tipo)
            ->whereNotNull('cidade')
            ->select('cidade')
            ->distinct()
            ->orderBy('cidade')
            ->pluck('cidade');

        // Aplicar filtros
        $this->applyFilters($query, $request);

        // Paginação com query string
        $imoveis = $query
            ->orderByDesc('destaque')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('imovel.lista', [
            'imoveis' => $imoveis,
            'tipo' => $tipo,
            'cidades' => $cidades,
        ]);
    }

    /**
     * Aplica filtros à query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function applyFilters($query, Request $request): void
    {
        // Filtro: tipo de imóvel
        if ($request->filled('tipo_imovel') && in_array($request->tipo_imovel, self::TIPOS_PERMITIDOS, true)) {
            $query->where('tipo_imovel', $request->tipo_imovel);
        }

        // Filtro: faixa de valores
        $this->applyPriceFilter($query, $request);

        // Filtro: cidade
        if ($request->filled('cidade')) {
            $query->where('cidade', $request->input('cidade'));
        }

        // Filtro: bairro
        if ($request->filled('bairro')) {
            $query->where('bairro', 'like', '%' . $request->input('bairro') . '%');
        }

        // Filtro: referência
        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%' . $request->input('referencia') . '%');
        }
    }

    /**
     * Aplica filtro de faixa de preço
     * 
     * @param \Illuminate\\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function applyPriceFilter($query, Request $request): void
    {
        $min = $this->normalizePrice($request->input('valor_min'));
        $max = $this->normalizePrice($request->input('valor_max'));

        // Inverte se necessário
        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        if ($min !== null) {
            $query->where('valor', '>=', $min);
        }

        if ($max !== null) {
            $query->where('valor', '<=', $max);
        }
    }

    /**
     * Normaliza valor para float (suporta formato BR e US)
     * 
     * @param mixed $value
     * @return float|null
     */
    private function normalizePrice($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float)$value;
        }

        // Remove caracteres não numéricos (exceto vírgula, ponto e sinal)
        $value = preg_replace('/[^\d,.-]/', '', $value);
        
        // Remove separadores de milhar e converte vírgula para ponto
        $value = str_replace(['.', ' '], '', $value);
        $value = str_replace(',', '.', $value);

        return (float)$value;
    }
}
