<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imovel;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Base para consultas (apenas imóveis disponíveis)
        $base = Imovel::query()->where('status', 'disponivel');

        // Lista de cidades existentes (distintas) para o filtro
        $cidades = (clone $base)
            ->whereNotNull('cidade')
            ->select('cidade')
            ->distinct()
            ->orderBy('cidade')
            ->pluck('cidade');

        // Query principal com imagens ordenadas
        $query = (clone $base)->with(['imagens' => function ($q) {
            $q->orderBy('ordem');
        }]);

        // ----- Filtros -----
        if ($request->filled('tipo_negocio')) {
            $query->where('tipo_negocio', $request->input('tipo_negocio'));
        }

        if ($request->filled('tipo_imovel')) {
            // se no DB for valor único, pode trocar por where('tipo_imovel', $request->input('tipo_imovel'))
            $query->where('tipo_imovel', 'like', '%' . $request->input('tipo_imovel') . '%');
        }

        // valores já chegam em "1234.56" via hidden no formulário
        $min = $request->input('valor_min');
        $max = $request->input('valor_max');

        // normaliza se vierem invertidos
        if ($min !== null && $min !== '' && $max !== null && $max !== '' && (float)$min > (float)$max) {
            [$min, $max] = [$max, $min];
        }

        if ($min !== null && $min !== '') {
            $query->where('valor', '>=', (float)$min);
        }
        if ($max !== null && $max !== '') {
            $query->where('valor', '<=', (float)$max);
        }

        if ($request->filled('cidade')) {
            $query->where('cidade', $request->input('cidade')); // igualdade pois vem de um select
        }

        if ($request->filled('referencia')) {
            $query->where('referencia', 'like', '%' . $request->input('referencia') . '%');
        }

        // Ordenação e paginação
        $imoveis = $query
            ->orderBy('destaque', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString(); // mantém filtros na paginação

        // Destaques
        $imoveisDestaque = (clone $base)
            ->with(['imagens' => function ($q) {
                $q->orderBy('ordem');
            }])
            ->where('destaque', true)
            ->limit(6)
            ->get();

        return view('home', compact('imoveis', 'imoveisDestaque', 'cidades'));
    }
}
