<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Imovel;
use App\Models\ImagemImovel;
use App\Models\CaracteristicaImovel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller ImovelAdminController
 * 
 * Gerencia o CRUD completo de imóveis no painel administrativo
 */
class ImovelAdminController extends Controller
{
    /**
     * Regras de validação base para imóveis
     * 
     * @var array<string, string>
     */
    private const VALIDATION_RULES = [
        'referencia' => 'nullable|string|max:100',
        'titulo' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'tipo_negocio' => 'required|in:aluguel,venda',
        'tipo_imovel' => 'required|string|max:100',
        'valor' => 'required|numeric|min:0',
        'valor_condominio' => 'nullable|numeric|min:0',
        'valor_iptu' => 'nullable|numeric|min:0',
        'endereco' => 'required|string|max:255',
        'numero' => 'nullable|string|max:20',
        'complemento' => 'nullable|string|max:100',
        'bairro' => 'required|string|max:100',
        'cidade' => 'required|string|max:100',
        'estado' => 'nullable|string|max:2',
        'cep' => 'nullable|string|max:10',
        'area_total' => 'nullable|numeric|min:0',
        'area_construida' => 'nullable|numeric|min:0',
        'quartos' => 'nullable|integer|min:0',
        'banheiros' => 'nullable|integer|min:0',
        'vagas_garagem' => 'nullable|integer|min:0',
        'suites' => 'nullable|integer|min:0',
        'andar' => 'nullable|integer',
        'mobiliado' => 'nullable|boolean',
        'status' => 'nullable|in:disponivel,vendido,alugado,indisponivel',
        'destaque' => 'nullable|boolean',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'imagens.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
    ];

    /**
     * Campos permitidos para mass assignment
     * 
     * @var array<string>
     */
    private const FILLABLE_FIELDS = [
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
     * Constructor: aplica middleware de autenticação
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Lista todos os imóveis com filtros
     * 
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Imovel::with('imagens');

        // Filtro: tipo de negócio
        if ($request->filled('tipo_negocio')) {
            $query->tipoNegocio($request->tipo_negocio);
        }

        // Filtro: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro: busca textual
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                    ->orWhere('referencia', 'like', "%{$search}%")
                    ->orWhere('endereco', 'like', "%{$search}%");
            });
        }

        $imoveis = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.imoveis.index', compact('imoveis'));
    }

    /**
     * Exibe formulário de criação
     * 
     * @return View
     */
    public function create(): View
    {
        return view('admin.imoveis.create');
    }

    /**
     * Armazena novo imóvel
     * 
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(self::VALIDATION_RULES);

        // Cria o imóvel
        $imovel = Imovel::create($request->only(self::FILLABLE_FIELDS));

        // Upload de imagens
        if ($request->hasFile('imagens')) {
            $this->storeImages($imovel, $request->file('imagens'));
        }

        // Salva características
        if ($request->filled('caracteristicas')) {
            $this->storeCaracteristicas($imovel, $request->caracteristicas);
        }

        return redirect()
            ->route('admin.imoveis.index')
            ->with('success', 'Imóvel cadastrado com sucesso!');
    }

    /**
     * Exibe detalhes de um imóvel
     * 
     * @param string $id
     * @return View
     */
    public function show(string $id): View
    {
        $imovel = Imovel::with(['imagens', 'caracteristicas'])->findOrFail($id);
        
        return view('admin.imoveis.show', compact('imovel'));
    }

    /**
     * Exibe formulário de edição
     * 
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $imovel = Imovel::with(['imagens', 'caracteristicas'])->findOrFail($id);
        
        return view('admin.imoveis.edit', compact('imovel'));
    }

    /**
     * Atualiza um imóvel existente
     * 
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $imovel = Imovel::findOrFail($id);
        
        $validated = $request->validate(self::VALIDATION_RULES);

        // Prepara dados para atualização
        $data = $request->only(self::FILLABLE_FIELDS);
        
        // Garante valores booleanos corretos
        $data['mobiliado'] = (bool) $request->input('mobiliado', $imovel->mobiliado);
        $data['destaque'] = (bool) $request->input('destaque', $imovel->destaque);

        $imovel->update($data);

        // Upload de novas imagens
        if ($request->hasFile('imagens')) {
            $ultimaOrdem = $imovel->imagens()->max('ordem') ?? -1;
            $this->storeImages($imovel, $request->file('imagens'), $ultimaOrdem + 1);
        }

        // Atualiza características
        if ($request->has('caracteristicas')) {
            $imovel->caracteristicas()->delete();
            
            if ($request->filled('caracteristicas')) {
                $this->storeCaracteristicas($imovel, $request->caracteristicas);
            }
        }

        return redirect()
            ->route('admin.imoveis.index')
            ->with('success', 'Imóvel atualizado com sucesso!');
    }

    /**
     * Remove um imóvel
     * 
     * @param string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $imovel = Imovel::with('imagens')->findOrFail($id);

        // As imagens são deletadas automaticamente via event no Model
        $imovel->delete();

        return redirect()
            ->route('admin.imoveis.index')
            ->with('success', 'Imóvel excluído com sucesso!');
    }

    /**
     * Remove uma imagem específica de um imóvel
     * 
     * @param Imovel $imovel
     * @param ImagemImovel $imagem
     * @return RedirectResponse
     */
    public function deleteImage(Imovel $imovel, ImagemImovel $imagem): RedirectResponse
    {
        // Verifica se a imagem pertence ao imóvel
        if ($imagem->imovel_id !== $imovel->id) {
            abort(403, 'Imagem não pertence a este imóvel.');
        }

        // Remove do storage
        if (Storage::disk('public')->exists($imagem->caminho_imagem)) {
            Storage::disk('public')->delete($imagem->caminho_imagem);
        }

        $imagem->delete();

        return back()->with('success', 'Imagem removida com sucesso!');
    }

    /**
     * Armazena múltiplas imagens para um imóvel
     * 
     * @param Imovel $imovel
     * @param array $imagens
     * @param int $ordemInicial
     * @return void
     */
    private function storeImages(Imovel $imovel, array $imagens, int $ordemInicial = 0): void
    {
        foreach ($imagens as $index => $imagem) {
            $path = $imagem->store('imoveis', 'public');
            
            ImagemImovel::create([
                'imovel_id' => $imovel->id,
                'caminho_imagem' => $path,
                'ordem' => $ordemInicial + $index,
            ]);
        }
    }

    /**
     * Armazena características de um imóvel
     * 
     * @param Imovel $imovel
     * @param string $caracteristicasString
     * @return void
     */
    private function storeCaracteristicas(Imovel $imovel, string $caracteristicasString): void
    {
        $caracteristicas = explode(',', $caracteristicasString);
        
        foreach ($caracteristicas as $caracteristica) {
            $caracteristica = trim($caracteristica);
            
            if ($caracteristica !== '') {
                CaracteristicaImovel::create([
                    'imovel_id' => $imovel->id,
                    'caracteristica' => $caracteristica,
                ]);
            }
        }
    }
}
