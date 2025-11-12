@extends('layouts.admin')

@section('title', 'Cadastrar Imóvel - Painel Administrativo')
@section('page-title', 'Cadastrar Imóvel')

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
</style>
@endpush

@section('content')
<form id="imovelForm" action="{{ route('admin.imoveis.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Informações principais -->
    <div class="card-custom">
        <h5 class="section-title">Informações Principais</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Referência</label>
                <input type="text" name="referencia" class="form-control" value="{{ old('referencia') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de Imóvel</label>
                <select name="tipo_imovel" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option value="apartamento" {{ old('tipo_imovel') == 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                    <option value="casa" {{ old('tipo_imovel') == 'casa' ? 'selected' : '' }}>Casa</option>
                    <option value="sobrado" {{ old('tipo_imovel') == 'sobrado' ? 'selected' : '' }}>Sobrado</option>
                    <option value="chacara" {{ old('tipo_imovel') == 'chacara' ? 'selected' : '' }}>Chácara</option>
                    <option value="terreno" {{ old('tipo_imovel') == 'terreno' ? 'selected' : '' }}>Terreno</option>
                    <option value="sala_comercial" {{ old('tipo_imovel') == 'sala_comercial' ? 'selected' : '' }}>Sala Comercial</option>
                    <option value="salao_comercial" {{ old('tipo_imovel') == 'salao_comercial' ? 'selected' : '' }}>Salão Comercial</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo de Negócio</label>
                <select name="tipo_negocio" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option value="aluguel" {{ old('tipo_negocio') == 'aluguel' ? 'selected' : '' }}>Aluguel</option>
                    <option value="venda" {{ old('tipo_negocio') == 'venda' ? 'selected' : '' }}>Venda</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Valor</label>
                <input type="text" name="valor" class="form-control money" value="{{ old('valor') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Valor Condomínio</label>
                <input type="text" name="valor_condominio" class="form-control money" value="{{ old('valor_condominio') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Valor IPTU</label>
                <input type="text" name="valor_iptu" class="form-control money" value="{{ old('valor_iptu') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="disponivel" {{ old('status') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                    <option value="vendido" {{ old('status') == 'vendido' ? 'selected' : '' }}>Vendido</option>
                    <option value="alugado" {{ old('status') == 'alugado' ? 'selected' : '' }}>Alugado</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Localização -->
    <div class="card-custom">
        <h5 class="section-title">Localização</h5>
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">CEP</label>
                <input type="text" name="cep" class="form-control" value="{{ old('cep') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Endereço</label>
                <input type="text" name="endereco" class="form-control" value="{{ old('endereco') }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Número</label>
                <input type="text" name="numero" class="form-control" value="{{ old('numero') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Complemento</label>
                <input type="text" name="complemento" class="form-control" value="{{ old('complemento') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Bairro</label>
                <input type="text" name="bairro" class="form-control" value="{{ old('bairro') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Cidade</label>
                <input type="text" name="cidade" class="form-control" value="{{ old('cidade') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado (UF)</label>
                @php
                    $ufs = [
                        'AC'=>'Acre','AL'=>'Alagoas','AP'=>'Amapá','AM'=>'Amazonas','BA'=>'Bahia',
                        'CE'=>'Ceará','DF'=>'Distrito Federal','ES'=>'Espírito Santo','GO'=>'Goiás',
                        'MA'=>'Maranhão','MT'=>'Mato Grosso','MS'=>'Mato Grosso do Sul','MG'=>'Minas Gerais',
                        'PA'=>'Pará','PB'=>'Paraíba','PR'=>'Paraná','PE'=>'Pernambuco','PI'=>'Piauí',
                        'RJ'=>'Rio de Janeiro','RN'=>'Rio Grande do Norte','RS'=>'Rio Grande do Sul',
                        'RO'=>'Rondônia','RR'=>'Roraima','SC'=>'Santa Catarina','SP'=>'São Paulo',
                        'SE'=>'Sergipe','TO'=>'Tocantins'
                    ];
                @endphp
                <select name="estado" class="form-select" required>
                    <option value="">Selecione...</option>
                    @foreach($ufs as $sigla => $nome)
                        <option value="{{ $sigla }}" {{ old('estado') == $sigla ? 'selected' : '' }}>
                            {{ $sigla }} - {{ $nome }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Características -->
    <div class="card-custom">
        <h5 class="section-title">Características</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Área Total (m²)</label>
                <input type="number" step="0.01" name="area_total" class="form-control" value="{{ old('area_total') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Área Construída (m²)</label>
                <input type="number" step="0.01" name="area_construida" class="form-control" value="{{ old('area_construida') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Quartos</label>
                <input type="number" name="quartos" class="form-control" value="{{ old('quartos') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Suítes</label>
                <input type="number" name="suites" class="form-control" value="{{ old('suites') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Banheiros</label>
                <input type="number" name="banheiros" class="form-control" value="{{ old('banheiros') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Vagas de Garagem</label>
                <input type="number" name="vagas_garagem" class="form-control" value="{{ old('vagas_garagem') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Andar</label>
                <input type="number" name="andar" class="form-control" value="{{ old('andar') }}">
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <div class="form-check mt-4">
                    <!-- Hidden para enviar 0 caso não marcado -->
                    <input type="hidden" name="destaque" value="0">
                    <input class="form-check-input" type="checkbox" name="destaque" value="1" {{ old('destaque') ? 'checked' : '' }}>
                    <label class="form-check-label">Destaque</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Descrição -->
    <div class="card-custom">
        <h5 class="section-title">Descrição</h5>
        <textarea name="descricao" class="form-control" rows="5">{{ old('descricao') }}</textarea>
    </div>

    <!-- Imagens -->
    <div class="card-custom">
        <h5 class="section-title">Imagens</h5>
        <input type="file" name="imagens[]" class="form-control" multiple>
    </div>

    <div class="d-flex justify-content-end">
        <a href="{{ route('admin.imoveis.index') }}" class="btn btn-secondary me-2">Cancelar</a>
        <button type="submit" class="btn btn-success">Salvar Imóvel</button>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script>
    $(function(){
        // Aplica máscara
        $('.money').maskMoney({
            prefix: 'R$ ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            affixesStay: true
        });

        // Converte antes de enviar
        $('#imovelForm').on('submit', function() {
            $('.money').each(function(){
                let valor = $(this).maskMoney('unmasked')[0];
                $(this).val(valor ? valor : '');
            });
        });
    });
</script>
@endpush
