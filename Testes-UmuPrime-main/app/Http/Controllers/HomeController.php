<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Controller HomeController
 * 
 * Gerencia a página inicial pública do site
 */
class HomeController extends Controller
{
    /**
     * Exibe a página inicial com listagem e filtros de imóveis
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Query base: apenas imóveis disponíveis
        $query = Imovel::query()
            ->disponivel()
            ->with(['imagens' => fn ($q) => $q->orderBy('ordem')]);

        // Lista de cidades para o filtro (apenas onde há imóveis disponíveis)
        $cidades = Imovel::query()
            ->disponivel()
            ->whereNotNull('cidade')
            ->select('cidade')
            ->distinct()
            ->orderBy('cidade')
            ->pluck('cidade');

        // Aplicar filtros
        $this->applyFilters($query, $request);

        // Paginação com query string (mantém filtros na navegação)
        $imoveis = $query
            ->orderByDesc('destaque')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        // Imóveis em destaque para exibição especial
        $imoveisDestaque = Imovel::query()
            ->disponivel()
            ->destaque()
            ->with(['imagens' => fn ($q) => $q->orderBy('ordem')])
            ->limit(6)
            ->get();

        return view('home', compact('imoveis', 'imoveisDestaque', 'cidades'));
    }

    /**
     * Aplica filtros à query de imóveis
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function applyFilters($query, Request $request): void
    {
        // Filtro: tipo de negócio (aluguel/venda)
        if ($request->filled('tipo_negocio')) {
            $query->tipoNegocio($request->input('tipo_negocio'));
        }

        // Filtro: tipo de imóvel
        if ($request->filled('tipo_imovel')) {
            $query->where('tipo_imovel', 'like', '%' . $request->input('tipo_imovel') . '%');
        }

        // Filtro: faixa de valores
        $this->applyPriceFilter($query, $request);

        // Filtro: cidade
        if ($request->filled('cidade')) {
            $query->where('cidade', $request->input('cidade'));
        }

        // Filtro: referência
        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%' . $request->input('referencia') . '%');
        }
    }

    /**
     * Aplica filtro de faixa de preço
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function applyPriceFilter($query, Request $request): void
    {
        $min = $request->input('valor_min');
        $max = $request->input('valor_max');

        // Normaliza se os valores vieram invertidos
        if ($min !== null && $min !== '' && $max !== null && $max !== '' && (float)$min > (float)$max) {
            [$min, $max] = [$max, $min];
        }

        if ($min !== null && $min !== '') {
            $query->where('valor', '>=', (float)$min);
        }

        if ($max !== null && $max !== '') {
            $query->where('valor', '<=', (float)$max);
        }
    }
}
