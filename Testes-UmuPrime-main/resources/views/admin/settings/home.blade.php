@extends('layouts.admin')

@section('title', 'Banner da Home - Configurações')
@section('page-title', 'Banner da Home')

@push('styles')
<style>
    .card-custom{
        background:#fff;border-radius:15px;padding:25px;
        box-shadow:0 5px 20px rgba(0,0,0,0.1);
        border-left:4px solid var(--primary-color);
        margin-bottom: 20px;
    }
    .image-preview-large{
        width:100%;
        max-height:340px;
        object-fit:cover;
        border-radius:12px;
        border:2px solid #eee;
        background:#f8f9fa;
    }
    .hint{color:#666;font-size:.95rem}
    .file-meta{font-size:.9rem;color:#666}
    .warning-text{color:#b02a37}
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Formulário -->
    <div class="col-lg-7">
        <div class="card-custom">
            <h5 class="mb-3 fw-bold">Imagem do Banner</h5>

            <form method="POST" action="{{ route('admin.settings.home.update') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Selecionar nova imagem</label>
                    <input type="file" name="hero_image" id="hero_image" class="form-control" accept="image/*">
                    @error('hero_image')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                    <div id="fileInfo" class="file-meta mt-2 d-none"></div>
                    <div id="dimWarn" class="warning-text mt-1 d-none">
                        Tamanho mínimo recomendado: 1600 × 600 px. Melhor resultado: 1920 × 756 px ou 2560 × 1008 px.
                    </div>
                    <div class="hint mt-2">
                        Recomendado: <strong>1920 × 756 px</strong> (ou <strong>2560 × 1008 px</strong> para telas grandes).<br>
                        Formato: <strong>WEBP</strong> (preferível) ou JPG/PNG. Máx: 4 MB.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pré-visualização da nova imagem</label>
                    <img id="livePreview" class="image-preview-large" alt="Pré-visualização">
                </div>

                <button class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Salvar
                </button>
            </form>
        </div>
    </div>

    <!-- Pré-visualização atual (sem URL e sem badge) -->
    <div class="col-lg-5">
        @php
            $settings = isset($settings) ? $settings : \App\Models\SiteSetting::singleton();
            $urlAtual = $settings->hero_image_url; // accessor do model
        @endphp

        <div class="card-custom">
            <h5 class="mb-3 fw-bold">Banner atual</h5>

            <img
                src="{{ $urlAtual }}"
                class="image-preview-large mb-2"
                alt="Banner atual"
                onerror="this.onerror=null;this.src='{{ asset('images/banner-default.webp') }}'">

            <p class="hint mt-3">
                Mantenha o assunto centralizado para evitar cortes.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('hero_image');
    const preview = document.getElementById('livePreview');
    const fileInfo = document.getElementById('fileInfo');
    const dimWarn = document.getElementById('dimWarn');

    // Oculta preview inicial
    preview.style.display = 'none';

    input.addEventListener('change', function(){
        const file = this.files && this.files[0] ? this.files[0] : null;
        if(!file){
            preview.src = '';
            preview.style.display = 'none';
            fileInfo.classList.add('d-none');
            dimWarn.classList.add('d-none');
            return;
        }

        // Info de arquivo
        const sizeKB = (file.size / 1024).toFixed(0);
        fileInfo.textContent = `Selecionado: ${file.name} — ${sizeKB} KB`;
        fileInfo.classList.remove('d-none');

        // Preview + checar dimensões
        const reader = new FileReader();
        reader.onload = function(e){
            preview.src = e.target.result;
            preview.style.display = 'block';

            const img = new Image();
            img.onload = function(){
                // Alerta se estiver abaixo do mínimo
                if (img.width < 1600 || img.height < 600) {
                    dimWarn.classList.remove('d-none');
                } else {
                    dimWarn.classList.add('d-none');
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
