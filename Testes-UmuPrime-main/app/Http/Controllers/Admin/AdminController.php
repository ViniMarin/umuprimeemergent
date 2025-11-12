<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Imovel;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

/**
 * Controller AdminController
 * 
 * Gerencia o dashboard administrativo
 */
class AdminController extends Controller
{
    /**
     * Constructor: aplica middleware de autenticação
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe o dashboard administrativo com estatísticas
     * 
     * @return View
     */
    public function dashboard(): View
    {
        // Estatísticas de imóveis
        $totalImoveis = Imovel::count();
        $imoveisAluguel = Imovel::tipoNegocio('aluguel')->count();
        $imoveisVenda = Imovel::tipoNegocio('venda')->count();
        $imoveisDestaque = Imovel::destaque()->count();
        $imoveisDisponiveis = Imovel::disponivel()->count();

        // Imóveis recentes (com primeira imagem)
        $recentesImoveis = Imovel::with(['imagens' => fn ($q) => $q->orderBy('ordem')])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get([
                'id',
                'referencia',
                'titulo',
                'tipo_negocio',
                'status',
                'valor',
                'created_at',
            ]);

        // Estatísticas de usuários (apenas para admins)
        $totalUsers = null;
        $adminUsers = null;
        
        if (Gate::allows('admin')) {
            $totalUsers = User::count();
            $adminUsers = User::admins()->count();
        }

        return view('admin.dashboard', compact(
            'totalImoveis',
            'imoveisAluguel',
            'imoveisVenda',
            'imoveisDestaque',
            'imoveisDisponiveis',
            'recentesImoveis',
            'totalUsers',
            'adminUsers'
        ));
    }
}
