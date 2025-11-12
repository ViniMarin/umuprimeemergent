@extends('layouts.app')

@section('title', 'Imóveis para ' . ucfirst($tipo) . ' - UmuPrime Imóveis')

@section('content')
<section class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color));">
  <div class="container">
    <div class="text-center">
      <h1 class="display-4 fw-bold text-dark">Imóveis para {{ ucfirst($tipo) }}</h1>
      <p class="lead text-dark">Encontre o imóvel perfeito para você</p>
    </div>
  </div>
</section>

<!-- Search Form (EXATAMENTE como a Home) -->
<div class="container">
  <div class="search-form">
    <form method="GET" action="{{ $tipo === 'aluguel' ? route('imoveis.aluguel') : route('imoveis.venda') }}">
      <div class="row g-3">
        <!-- Negócio (somente para manter layout igual; bloqueado) -->
        <div class="col-md-2">
          <label class="form-label">Negócio</label>
          <select class="form-select" disabled>
            <option value="aluguel" {{ $tipo === 'aluguel' ? 'selected' : '' }}>Alugar</option>
            <option value="venda"   {{ $tipo === 'venda'   ? 'selected' : '' }}>Comprar</option>
          </select>
        </div>

        <!-- Tipo do Imóvel -->
        <div class="col-md-2">
          <label class="form-label">Tipo do Imóvel</label>
          <select name="tipo_imovel" class="form-select">
            <option value="">Selecione</option>
            <option value="apartamento"   {{ request('tipo_imovel') == 'apartamento'   ? 'selected' : '' }}>Apartamento</option>
            <option value="casa"          {{ request('tipo_imovel') == 'casa'          ? 'selected' : '' }}>Casa</option>
            <option value="sobrado"       {{ request('tipo_imovel') == 'sobrado'       ? 'selected' : '' }}>Sobrado</option>
            <option value="chacara"       {{ request('tipo_imovel') == 'chacara'       ? 'selected' : '' }}>Chácara</option>
            <option value="terreno"       {{ request('tipo_imovel') == 'terreno'       ? 'selected' : '' }}>Terreno</option>
            <option value="sala_comercial"{{ request('tipo_imovel') == 'sala_comercial'? 'selected' : '' }}>Sala Comercial</option>
            <option value="salao_comercial"{{ request('tipo_imovel') == 'salao_comercial'? 'selected' : '' }}>Salão Comercial</option>
          </select>
        </div>

        <!-- Valor mínimo (mask + hidden) -->
        <div class="col-md-2">
          <label class="form-label">Valor mínimo</label>
          <input type="hidden" name="valor_min" id="valor_min" value="{{ request('valor_min') }}">
          <input
            type="text"
            id="valor_min_mask"
            class="form-control"
            inputmode="numeric"
            autocomplete="off"
            placeholder="R$ 0,00"
            value="{{ request('valor_min') !== null && request('valor_min') !== '' ? 'R$ '.number_format((float)request('valor_min'), 2, ',', '.') : '' }}"
          >
        </div>

        <!-- Valor máximo (mask + hidden) -->
        <div class="col-md-2">
          <label class="form-label">Valor máximo</label>
          <input type="hidden" name="valor_max" id="valor_max" value="{{ request('valor_max') }}">
          <input
            type="text"
            id="valor_max_mask"
            class="form-control"
            inputmode="numeric"
            autocomplete="off"
            placeholder="R$ 0,00"
            value="{{ request('valor_max') !== null && request('valor_max') !== '' ? 'R$ '.number_format((float)request('valor_max'), 2, ',', '.') : '' }}"
          >
        </div>

        <!-- Cidade (somente opções existentes) -->
        <div class="col-md-2">
          <label class="form-label">Cidade</label>
          <select name="cidade" class="form-select">
            <option value="">Todas</option>
            @foreach(($cidades ?? []) as $cidade)
              <option value="{{ $cidade }}" {{ request('cidade') === $cidade ? 'selected' : '' }}>
                {{ $cidade }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Botão (igual Home) -->
        <div class="col-md-2">
          <label class="form-label">&nbsp;</label>
          <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-search"></i> Pesquisar
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <div class="results-info mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
          <h5 class="mb-0">
            {{ $imoveis->total() }} imóvel(is) encontrado(s)
            @if(request()->hasAny(['tipo_imovel','valor_min','valor_max','cidade','bairro']))
              <small class="text-muted">com os filtros aplicados</small>
            @endif
          </h5>
        </div>
        <div class="col-md-6 text-end">
          @if(request()->hasAny(['tipo_imovel','valor_min','valor_max','cidade','bairro']))
            <a href="{{ $tipo === 'aluguel' ? route('imoveis.aluguel') : route('imoveis.venda') }}"
               class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-times"></i> Limpar Filtros
            </a>
          @endif
        </div>
      </div>
    </div>

    @if($imoveis->count())
      <div class="row">
        @foreach($imoveis as $imovel)
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="property-card">
              <div class="property-image"
                   style="background-image:url('{{ $imovel->primeira_imagem ? asset('storage/'.$imovel->primeira_imagem->caminho_imagem) : 'https://via.placeholder.com/400x250?text=Sem+Imagem' }}')">
                <div class="property-badge">{{ ucfirst($imovel->tipo_negocio) }}</div>
                <div class="property-price">{{ $imovel->valor_formatado }}</div>
              </div>
              <div class="property-info">
                <h5 class="property-title">{{ $imovel->titulo }}</h5>
                <p class="property-location">
                  <i class="fas fa-map-marker-alt"></i>
                  {{ $imovel->endereco }}@if($imovel->numero), {{ $imovel->numero }}@endif
                  <br>{{ $imovel->bairro }} - {{ $imovel->cidade }}
                </p>
                <div class="property-features">
                  @if($imovel->quartos)<div class="feature-item"><i class="fas fa-bed"></i> {{ $imovel->quartos }}</div>@endif
                  @if($imovel->banheiros)<div class="feature-item"><i class="fas fa-bath"></i> {{ $imovel->banheiros }}</div>@endif
                  @if($imovel->area_construida)<div class="feature-item"><i class="fas fa-ruler-combined"></i> {{ $imovel->area_construida }}m²</div>@endif
                  @if($imovel->vagas_garagem)<div class="feature-item"><i class="fas fa-car"></i> {{ $imovel->vagas_garagem }}</div>@endif
                </div>
                <a href="{{ route('imovel.show', $imovel->id) }}" class="btn btn-primary w-100"><i class="fas fa-eye"></i> Ver Detalhes</a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="d-flex justify-content-center mt-5">
        {{ $imoveis->appends(request()->query())->links() }}
      </div>
    @else
      <div class="text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4>Nenhum imóvel encontrado</h4>
        <p class="text-muted">Tente ajustar os filtros de busca ou volte mais tarde.</p>
      </div>
    @endif
  </div>
</section>

<section class="py-5" style="background-color: var(--primary-color);">
  <div class="container text-center">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <h2 class="display-6 fw-bold text-dark">Tem um imóvel para {{ $tipo === 'aluguel' ? 'alugar' : 'vender' }}?</h2>
        <p class="lead text-dark mb-4">Nossa equipe especializada pode ajudar você a {{ $tipo === 'aluguel' ? 'alugar' : 'vender' }} seu imóvel rapidamente e pelo melhor preço!</p>
        <a href="{{ route('contato') }}" class="btn btn-dark btn-lg me-3"><i class="fas fa-home"></i> Anunciar Imóvel</a>
        <a href="https://wa.me/5544999999999?text=Olá! Gostaria de anunciar meu imóvel." class="btn btn-success btn-lg" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
      </div>
    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
.search-filters,.search-form{background:#fff;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.1)}
.results-info{border-bottom:1px solid #eee;padding-bottom:15px}
.property-meta{border-top:1px solid #eee;padding-top:10px}
.no-results{background:#fff;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,.1);margin:50px 0}
</style>
@endpush

@push('scripts')
<script>
(() => {
  function maskFromNumber(num){
    if (num === '' || num === null || num === undefined) return '';
    const n = Number(num);
    if (Number.isNaN(n)) return '';
    return 'R$ ' + n.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
  function toNumberFromMask(str){
    if (!str) return '';
    const digits = String(str).replace(/[^\d]/g, '');
    if (!digits) return '';
    return (parseInt(digits, 10) / 100).toFixed(2);
  }
  function bindCurrency(maskId, hiddenId){
    const $mask = document.getElementById(maskId);
    const $hidden = document.getElementById(hiddenId);
    if (!$mask || !$hidden) return;
    if ($hidden.value) $mask.value = maskFromNumber($hidden.value);
    $mask.addEventListener('input', () => {
      const numeric = toNumberFromMask($mask.value);
      $hidden.value = numeric || '';
      $mask.value   = numeric ? maskFromNumber(numeric) : '';
      const len = $mask.value.length;
      $mask.setSelectionRange(len, len);
    });
    $mask.addEventListener('blur', () => { if (!$mask.value) $hidden.value = ''; });
  }
  bindCurrency('valor_min_mask', 'valor_min');
  bindCurrency('valor_max_mask', 'valor_max');
})();
</script>
@endpush
