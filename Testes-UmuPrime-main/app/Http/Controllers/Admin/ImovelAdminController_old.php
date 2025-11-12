<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Imovel;
use App\Models\ImagemImovel;
use App\Models\CaracteristicaImovel;

class ImovelAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** LISTA */
    public function index(Request $request)
    {
        $query = Imovel::with('imagens');

        if ($request->filled('tipo_negocio')) {
            $query->where('tipo_negocio', $request->tipo_negocio);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('referencia', 'like', "%{$search}%")
                  ->orWhere('endereco', 'like', "%{$search}%");
            });
        }

        $imoveis = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.imoveis.index', compact('imoveis'));
    }

    /** CREATE FORM */
    public function create()
    {
        return view('admin.imoveis.create');
    }

    /** STORE */
    public function store(Request $request)
    {
        $request->validate([
            'referencia'   => 'nullable|string|max:100',
            'titulo'       => 'required',
            'tipo_negocio' => 'required|in:aluguel,venda',
            'tipo_imovel'  => 'required',
            'valor'        => 'required|numeric',
            'endereco'     => 'required',
            'bairro'       => 'required',
            'cidade'       => 'required',
            'imagens.*'    => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imovel = Imovel::create($request->only([
            'referencia','titulo','descricao','tipo_negocio','tipo_imovel','valor',
            'valor_condominio','valor_iptu','endereco','numero','complemento',
            'bairro','cidade','estado','cep','area_total','area_construida',
            'quartos','banheiros','vagas_garagem','suites','mobiliado',
            'status','destaque','latitude','longitude'
        ]));

        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $index => $imagem) {
                $path = $imagem->store('imoveis', 'public');
                ImagemImovel::create([
                    'imovel_id' => $imovel->id,
                    'caminho_imagem' => $path,
                    'ordem' => $index,
                ]);
            }
        }

        if ($request->filled('caracteristicas')) {
            $caracteristicas = explode(',', $request->caracteristicas);
            foreach ($caracteristicas as $caracteristica) {
                $val = trim($caracteristica);
                if ($val !== '') {
                    CaracteristicaImovel::create([
                        'imovel_id' => $imovel->id,
                        'caracteristica' => $val,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.imoveis.index')
            ->with('success', 'Imóvel cadastrado com sucesso!');
    }

    /** SHOW */
    public function show(string $id)
    {
        $imovel = Imovel::with(['imagens','caracteristicas'])->findOrFail($id);
        return view('admin.imoveis.show', compact('imovel'));
    }

    /** EDIT FORM */
    public function edit(string $id)
    {
        $imovel = Imovel::with(['imagens','caracteristicas'])->findOrFail($id);
        return view('admin.imoveis.edit', compact('imovel'));
    }

    /** UPDATE */
    public function update(Request $request, string $id)
    {
        $imovel = Imovel::findOrFail($id);

        $request->validate([
            'referencia'   => 'nullable|string|max:100',
            'titulo'       => 'required',
            'tipo_negocio' => 'required|in:aluguel,venda',
            'tipo_imovel'  => 'required',
            'valor'        => 'required|numeric',
            'endereco'     => 'required',
            'bairro'       => 'required',
            'cidade'       => 'required',
            'imagens.*'    => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'referencia','titulo','descricao','tipo_negocio','tipo_imovel','valor',
            'valor_condominio','valor_iptu','endereco','numero','complemento',
            'bairro','cidade','estado','cep','area_total','area_construida',
            'quartos','banheiros','vagas_garagem','suites','mobiliado',
            'status','destaque','latitude','longitude'
        ]);

        $data['mobiliado'] = (bool) ($request->input('mobiliado', $imovel->mobiliado));
        $data['destaque']  = (bool) ($request->input('destaque',  $imovel->destaque));

        $imovel->update($data);

        if ($request->hasFile('imagens')) {
            $ultimaOrdem = $imovel->imagens()->max('ordem') ?? -1;
            foreach ($request->file('imagens') as $index => $imagem) {
                $path = $imagem->store('imoveis', 'public');
                ImagemImovel::create([
                    'imovel_id' => $imovel->id,
                    'caminho_imagem' => $path,
                    'ordem' => $ultimaOrdem + $index + 1,
                ]);
            }
        }

        if ($request->has('caracteristicas')) {
            $imovel->caracteristicas()->delete();
            if ($request->filled('caracteristicas')) {
                $caracteristicas = explode(',', $request->caracteristicas);
                foreach ($caracteristicas as $caracteristica) {
                    $val = trim($caracteristica);
                    if ($val !== '') {
                        CaracteristicaImovel::create([
                            'imovel_id' => $imovel->id,
                            'caracteristica' => $val,
                        ]);
                    }
                }
            }
        }

        return redirect()
            ->route('admin.imoveis.index')
            ->with('success', 'Imóvel atualizado com sucesso!');
    }

    /** DESTROY */
    public function destroy(string $id)
    {
        $imovel = Imovel::with('imagens')->findOrFail($id);

        foreach ($imovel->imagens as $imagem) {
            Storage::disk('public')->delete($imagem->caminho_imagem);
        }

        $imovel->delete();

        return redirect()
            ->route('admin.imoveis.index')
            ->with('success', 'Imóvel excluído com sucesso!');
    }

    /** DELETE ONE IMAGE */
    public function deleteImage(Imovel $imovel, ImagemImovel $imagem)
    {
        if ($imagem->imovel_id !== $imovel->id) {
            abort(403, 'Imagem não pertence a este imóvel.');
        }

        if (Storage::disk('public')->exists($imagem->caminho_imagem)) {
            Storage::disk('public')->delete($imagem->caminho_imagem);
        }

        $imagem->delete();

        return back()->with('success', 'Imagem removida com sucesso!');
    }
}
