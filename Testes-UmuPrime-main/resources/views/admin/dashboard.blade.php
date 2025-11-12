@extends('layouts.admin')

@section('title', 'Dashboard - Painel Administrativo')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stat-card{
        background:#fff;border-radius:15px;padding:25px;text-align:center;
        box-shadow:0 5px 20px rgba(0,0,0,.1);transition:.2s ease-in-out;
    }
    .stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 25px rgba(0,0,0,.15)}
    .stat-icon{font-size:32px;margin-bottom:10px;color:var(--primary-color)}
    .stat-number{font-size:1.8rem;font-weight:700;color:var(--text-dark)}
    .stat-label{font-size:.95rem;color:#666}

    .card-custom{
        background:#fff;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,.1);
        margin-bottom:20px;padding:25px;
    }

    .table-card{background:#fff;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,.1);margin-bottom:20px;overflow:hidden}
    .table-card .card-header{
        background:var(--primary-color);color:var(--secondary-color);
        font-weight:600;padding:15px 20px;border:none;
    }
    .image-preview{width:80px;height:60px;object-fit:cover;border-radius:8px;border:2px solid #eee}
</style>
@endpush

@section('content')
<!-- Estatísticas -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-home"></i></div>
            <div class="stat-number">{{ $totalImoveis }}</div>
            <div class="stat-label">Total de Imóveis</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-key"></i></div>
            <div class="stat-number">{{ $imoveisAluguel }}</div>
            <div class="stat-label">Para Aluguel</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-number">{{ $imoveisVenda }}</div>
            <div class="stat-label">Para Venda</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-star"></i></div>
            <div class="stat-number">{{ $imoveisDestaque }}</div>
            <div class="stat-label">Em Destaque</div>
        </div>
    </div>
</div>

<!-- Ações rápidas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card-custom">
            <h5 class="mb-3 fw-bold">Ações Rápidas</h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.imoveis.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>Novo Imóvel
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.imoveis.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-list me-2"></i>Listar Imóveis
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-primary w-100">
                        <i class="fas fa-external-link-alt me-2"></i>Ver Site
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.imoveis.index', ['status' => 'disponivel']) }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-check me-2"></i>Disponíveis ({{ $imoveisDisponiveis }})
                    </a>
                </div>

                @can('admin')
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-users me-2"></i>Gerenciar Usuários
                    </a>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- Imóveis recentes -->
@if($recentesImoveis->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Imóveis Recentes</h5>
                <a href="{{ route('admin.imoveis.index') }}" class="btn btn-sm btn-outline-dark">Ver Todos</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Referência</th>
                            <th>Título</th>
                            <th>Negócio</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th style="width:120px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentesImoveis as $imovel)
                        <tr>
                            <td>
                                @php $img = optional($imovel->imagens->first())->caminho_imagem; @endphp
                                @if($img)
                                    <img src="{{ asset('storage/'.$img) }}" alt="{{ $imovel->titulo }}" class="image-preview">
                                @else
                                    <div class="image-preview bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $imovel->referencia }}</strong></td>
                            <td>{{ $imovel->titulo }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">{{ ucfirst($imovel->tipo_negocio) }}</span>
                            </td>
                            <td>
                                @if(method_exists($imovel, 'getValorFormatadoAttribute') || isset($imovel->valor_formatado))
                                    {{ $imovel->valor_formatado }}
                                @else
                                    R$ {{ number_format($imovel->valor ?? 0, 2, ',', '.') }}
                                @endif
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
                                        <span class="badge bg-secondary">{{ ucfirst($imovel->status ?? '-') }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.imoveis.edit', $imovel->id) }}" class="btn btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.imoveis.show', $imovel->id) }}" class="btn btn-outline-primary" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Sistema / Links -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card-custom">
            <h5 class="fw-bold mb-3">Informações do Sistema</h5>
            <div class="row">
                <div class="col-6">
                    <small class="text-muted">Versão Laravel:</small><br>
                    <strong>{{ app()->version() }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted">Usuário Logado:</small><br>
                    <strong>{{ Auth::user()->name }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom">
            <h5 class="fw-bold mb-3">Links Úteis</h5>
            <div class="d-grid gap-2">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-external-link-alt me-2"></i>Visualizar Site
                </a>
                <a href="{{ route('contato') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-envelope me-2"></i>Página de Contato
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
