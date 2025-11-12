<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Imovel;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function __construct()
    {
        // exige usuário autenticado para acessar o painel
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // contagens principais
        $totalImoveis       = Imovel::count();
        $imoveisAluguel     = Imovel::where('tipo_negocio', 'aluguel')->count();
        $imoveisVenda       = Imovel::where('tipo_negocio', 'venda')->count();
        $imoveisDestaque    = Imovel::where('destaque', true)->count();
        $imoveisDisponiveis = Imovel::where('status', 'disponivel')->count();

        // últimos imóveis (com imagens)
        $recentesImoveis = Imovel::with(['imagens' => function ($q) {
                                    $q->oldest();
                                }])
                                ->orderByDesc('created_at')
                                ->limit(5)
                                ->get([
                                    'id', 'referencia', 'titulo', 'tipo_negocio', 'status',
                                    'valor', 'created_at'
                                ]);

        // cards/links de usuários só são úteis para admin (evita consulta desnecessária)
        $totalUsers  = null;
        $adminUsers  = null;
        if (Gate::allows('admin')) {
            $totalUsers = User::count();
            $adminUsers = User::where('is_admin', true)->count();
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
