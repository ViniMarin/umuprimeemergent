@extends('layouts.admin')

@section('title', 'Gerenciar Imóveis - Painel Administrativo')
@section('page-title', 'Gerenciar Imóveis')

@push('styles')
<style>
    .card-custom {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-left: 4px solid var(--primary-color);
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
    }

    .section-title {
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 8px;
        margin-bottom: 20px;
        color: var(--secondary-color);
        font-weight: 600;
    }

    .image-preview {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #eee;
    }

    .table thead th {
        background: var(--primary-color);
        color: var(--secondary-color);
    }

    /* Ajuste para alinhar os botões de ação */
    .btn-action {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
</style>
@endpush

@section('content')
<!-- Filtros -->
<div class="card-custom">
    <h5 class="section-title">Filtros de Busca</h5>
    <form method="GET" action="{{ route('admin.imoveis.index') }}">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Buscar</label>
                <input type="text" name="search" class="form-control"
                       placeholder="Título, referência ou endereço..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tipo de Negócio</label>
                <select name="tipo_negocio" class="form-select">
                    <option value="">Todos</option>
                    <option value="aluguel" {{ request('tipo_negocio') == 'aluguel' ? 'selected' : '' }}>Aluguel</option>
                    <option value="venda" {{ request('tipo_negocio') == 'venda' ? 'selected' : '' }}>Venda</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="disponivel" {{ request('status') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                    <option value="vendido" {{ request('status') == 'vendido' ? 'selected' : '' }}>Vendido</option>
                    <option value="alugado" {{ request('status') == 'alugado' ? 'selected' : '' }}>Alugado</option>
                </select>
            </div>

            <!-- Linha de botões -->
            <div class="col-md-5 d-flex justify-content-between align-items-end">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.imoveis.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Limpar
                    </a>
                </div>
                <a href="{{ route('admin.imoveis.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Novo Imóvel
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Info resultados -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="mb-0">
        {{ $imoveis->total() }} imóvel(is) encontrado(s)
        @if(request()->hasAny(['search', 'tipo_negocio', 'status']))
            <small class="text-muted">com os filtros aplicados</small>
        @endif
    </h6>
    <div class="text-muted">
        Página {{ $imoveis->currentPage() }} de {{ $imoveis->lastPage() }}
    </div>
</div>

@if($imoveis->count() > 0)
<!-- Tabela -->
<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th width="80">Imagem</th>
                    <th>Referência</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Localização</th>
                    <th>Status</th>
                    <th>Destaque</th>
                    <th width="180">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($imoveis as $imovel)
                <tr>
                    <td>
                        @php $primeiraImagem = $imovel->imagens->first(); @endphp
                        @if($primeiraImagem)
                            <img src="{{ asset('storage/' . $primeiraImagem->caminho_imagem) }}"
                                 alt="{{ $imovel->titulo }}"
                                 class="image-preview">
                        @else
                            <div class="image-preview bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-image text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $imovel->referencia }}</strong><br>
                        <small class="text-muted">{{ $imovel->created_at->format('d/m/Y') }}</small>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $imovel->titulo }}</div>
                        <small class="text-muted">{{ ucfirst($imovel->tipo_imovel) }}</small>
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ ucfirst($imovel->tipo_negocio) }}</span>
                    </td>
                    <td>
                        <strong>R$ {{ number_format($imovel->valor, 2, ',', '.') }}</strong>
                    </td>
                    <td>
                        <div>{{ $imovel->bairro }}</div>
                        <small class="text-muted">{{ $imovel->cidade }}</small>
                    </td>
                    <td>
                        @switch($imovel->status)
                            @case('disponivel')
                                <span class="badge bg-success">Disponível</span>
                                @break
                            @case('vendido')
                                <span class="badge bg-danger">Vendido</span>
                                @break
                            @case('alugado')
                                <span class="badge bg-warning text-dark">Alugado</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ ucfirst($imovel->status) }}</span>
                        @endswitch
                    </td>
                    <td>
                        @if($imovel->destaque)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> Destaque
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admin.imoveis.show', $imovel->id) }}"
                               class="btn btn-sm btn-primary btn-action" title="Visualizar">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.imoveis.edit', $imovel->id) }}"
                               class="btn btn-sm btn-warning btn-action" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger btn-action"
                                    onclick="confirmDelete({{ $imovel->id }}, '{{ $imovel->referencia }}')"
                                    title="Excluir">
                                <i class="fas fa-trash"></i>
                            </button>
                            <a href="{{ route('imovel.show', $imovel->id) }}"
                               target="_blank"
                               class="btn btn-sm btn-info btn-action"
                               title="Ver no site">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Paginação -->
<div class="d-flex justify-content-center mt-3">
    {{ $imoveis->appends(request()->query())->links() }}
</div>

@else
<!-- Nenhum resultado -->
<div class="card-custom text-center py-5">
    <i class="fas fa-search fa-3x text-muted mb-3"></i>
    <h5>Nenhum imóvel encontrado</h5>
    <p class="text-muted mb-4">
        @if(request()->hasAny(['search', 'tipo_negocio', 'status']))
            Não encontramos imóveis que correspondam aos filtros aplicados.
            <br>Tente ajustar os critérios de busca.
        @else
            Você ainda não cadastrou nenhum imóvel.
            <br>Comece cadastrando seu primeiro imóvel.
        @endif
    </p>
    <div class="d-flex gap-2 justify-content-center">
        @if(request()->hasAny(['search', 'tipo_negocio', 'status']))
            <a href="{{ route('admin.imoveis.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-refresh"></i> Ver Todos os Imóveis
            </a>
        @endif
        <a href="{{ route('admin.imoveis.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Cadastrar Imóvel
        </a>
    </div>
</div>
@endif

<!-- Modal de confirmação -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir o imóvel <strong id="imovelRef"></strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Esta ação não pode ser desfeita. Todas as imagens e dados relacionados serão removidos permanentemente.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Excluir Imóvel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(imovelId, referencia) {
    document.getElementById('imovelRef').textContent = referencia;
    document.getElementById('deleteForm').action = `/admin/imoveis/${imovelId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Auto-refresh a cada 30s se não houver filtros
@if(!request()->hasAny(['search', 'tipo_negocio', 'status']))
setTimeout(function() {
    if (document.visibilityState === 'visible') {
        window.location.reload();
    }
}, 30000);
@endif
</script>
@endpush
