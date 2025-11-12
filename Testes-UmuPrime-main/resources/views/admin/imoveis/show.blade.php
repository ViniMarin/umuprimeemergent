@extends('layouts.admin')

@section('title', 'Detalhes do Imóvel - UmuPrime Imóveis')
@section('page-title', 'Detalhes do Imóvel')

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
        font-size: 1.1rem;
    }

    .image-preview {
        width: 120px;
        height: 90px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 10px;
        border: 2px solid #eee;
    }

    .badge-custom {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
    }
</style>
@endpush

@section('content')
<div class="card-custom">
    <h5 class="section-title">Informações do Imóvel</h5>
    <p><strong>Referência:</strong> {{ $imovel->referencia }}</p>
    <p><strong>Título:</strong> {{ $imovel->titulo }}</p>
    <p><strong>Descrição:</strong> {{ $imovel->descricao }}</p>
    <p>
        <strong>Tipo de Negócio:</strong>
        <span class="badge bg-primary">{{ ucfirst($imovel->tipo_negocio) }}</span>
    </p>
    <p><strong>Tipo de Imóvel:</strong> {{ $imovel->tipo_imovel }}</p>
    <p><strong>Valor:</strong> R$ {{ number_format($imovel->valor, 2, ',', '.') }}</p>
    <p><strong>Status:</strong> 
        @switch($imovel->status)
            @case('disponivel') <span class="badge bg-success">Disponível</span> @break
            @case('vendido') <span class="badge bg-danger">Vendido</span> @break
            @case('alugado') <span class="badge bg-warning text-dark">Alugado</span> @break
            @default <span class="badge bg-secondary">{{ ucfirst($imovel->status) }}</span>
        @endswitch
    </p>
</div>

<div class="card-custom">
    <h5 class="section-title">Localização</h5>
    <p><strong>Cidade:</strong> {{ $imovel->cidade }}</p>
    <p><strong>Bairro:</strong> {{ $imovel->bairro }}</p>
    <p><strong>Endereço:</strong> {{ $imovel->endereco }}</p>
</div>

@if($imovel->caracteristicas->count() > 0)
<div class="card-custom">
    <h5 class="section-title">Características</h5>
    <ul>
        @foreach($imovel->caracteristicas as $caracteristica)
            <li>{{ $caracteristica->caracteristica }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card-custom">
    <h5 class="section-title">Imagens</h5>
    <div class="d-flex flex-wrap">
        @foreach($imovel->imagens as $imagem)
            <img src="{{ asset('storage/' . $imagem->caminho_imagem) }}" class="image-preview" alt="Imagem do imóvel">
        @endforeach
    </div>
</div>

<!-- Botões -->
<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('admin.imoveis.index') }}" class="btn btn-outline-primary">Voltar</a>
    <a href="{{ route('admin.imoveis.edit', $imovel->id) }}" class="btn btn-warning">Editar</a>
</div>
@endsection
