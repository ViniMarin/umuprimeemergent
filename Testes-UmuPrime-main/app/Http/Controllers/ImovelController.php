<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imovel;

class ImovelController extends Controller
{
    public function show($id)
    {
        $imovel = Imovel::with(['imagens' => function($q) {
            $q->orderBy('ordem');
        }, 'caracteristicas'])->findOrFail($id);

        // Imóveis relacionados (mesmo tipo de negócio e cidade)
        $imoveisRelacionados = Imovel::with(['imagens' => function($q) {
            $q->orderBy('ordem');
        }])
        ->where('tipo_negocio', $imovel->tipo_negocio)
        ->where('cidade', $imovel->cidade)
        ->where('id', '!=', $imovel->id)
        ->where('status', 'disponivel')
        ->limit(4)
        ->get();

        return view('imovel.show', compact('imovel', 'imoveisRelacionados'));
    }

    public function aluguel(Request $request)
    {
        return $this->listarPorTipo('aluguel', $request);
    }

    public function venda(Request $request)
    {
        return $this->listarPorTipo('venda', $request);
    }

    /**
     * Lista imóveis por tipo de negócio com filtros padronizados.
     */
    private function listarPorTipo(string $tipo, Request $request)
    {
        // Base: somente disponíveis do tipo desejado
        $base = Imovel::query()
            ->where('status', 'disponivel')
            ->where('tipo_negocio', $tipo);

        // Cidades existentes para o filtro (apenas onde há imóvel desse tipo)
        $cidades = (clone $base)
            ->whereNotNull('cidade')
            ->select('cidade')
            ->distinct()
            ->orderBy('cidade')
            ->pluck('cidade');

        // Query principal (com imagens ordenadas)
        $query = (clone $base)->with(['imagens' => function($q) {
            $q->orderBy('ordem');
        }]);

        // Tipos permitidos (ajuste se quiser)
        $tiposPermitidos = [
            'apartamento','casa','terreno','sala_comercial','salao_comercial','chacara','sobrado'
        ];

        // --- Filtros ---
        if ($request->filled('tipo_imovel') && in_array($request->tipo_imovel, $tiposPermitidos, true)) {
            // se no DB for valor único, ótimo; se for texto livre, pode usar like
            $query->where('tipo_imovel', $request->tipo_imovel);
        }

        // Suporta tanto "R$ 1.234,56" quanto "1234.56"
        $toFloat = function ($v) {
            if ($v === null || $v === '') return null;
            if (is_numeric($v)) return (float)$v;
            // remove tudo que não é dígito, vírgula, ponto ou sinal
            $v = preg_replace('/[^\d,.-]/', '', $v);
            // remove separadores de milhar e troca vírgula por ponto
            $v = str_replace(['.', ' '], '', $v);
            $v = str_replace(',', '.', $v);
            return (float)$v;
        };

        $min = $toFloat($request->input('valor_min'));
        $max = $toFloat($request->input('valor_max'));

        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        if ($min !== null) { $query->where('valor', '>=', $min); }
        if ($max !== null) { $query->where('valor', '<=', $max); }

        if ($request->filled('cidade')) {
            $query->where('cidade', $request->input('cidade')); // vem de <select>
        }

        if ($request->filled('bairro')) {
            $query->where('bairro', 'like', '%'.$request->input('bairro').'%');
        }

        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%'.$request->input('referencia').'%');
        }

        // Ordenação e paginação
        $imoveis = $query
            ->orderBy('destaque', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString(); // mantém os filtros na paginação

        // Usa uma única view para ambos, passando $tipo e $cidades para os filtros
        return view('imovel.lista', [
            'imoveis'  => $imoveis,
            'tipo'     => $tipo,     // 'aluguel' ou 'venda'
            'cidades'  => $cidades,  // lista para o select
        ]);
    }
}
