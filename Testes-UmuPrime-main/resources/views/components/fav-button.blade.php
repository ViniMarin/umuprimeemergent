@php
    // Primeira imagem para exibir no favoritos
    $img = optional($imovel->imagens->first())->caminho_imagem
        ? asset('storage/' . $imovel->imagens->first()->caminho_imagem)
        : asset('images/no-image.jpg');
@endphp

<button
  class="btn-heart js-fav-toggle"
  type="button"
  aria-label="Favoritar"
  aria-pressed="false"
  data-id="{{ $imovel->id }}"
  data-title="{{ $imovel->titulo }}"
  data-preco-formatado="{{ $imovel->valor_formatado }}"
  data-negocio="{{ $imovel->tipo_negocio }}"
  data-cidade="{{ $imovel->cidade }}"
  data-bairro="{{ $imovel->bairro }}"
  data-quartos="{{ $imovel->quartos }}"
  data-suites="{{ $imovel->suites }}"
  data-banheiros="{{ $imovel->banheiros }}"
  data-garagem="{{ $imovel->vagas_garagem }}"
  data-area="{{ $imovel->area_construida ?? $imovel->area_total }}"
  data-img="{{ $img }}"
  data-link="{{ route('imovel.show', $imovel->id) }}"
>
  <i class="fa-solid fa-heart icon-heart" aria-hidden="true"></i>
</button>

@once
@push('styles')
<style>
  /* Botão (container) */
  .btn-heart{
    background:#fff;border:0;width:40px;height:40px;border-radius:50%;
    display:inline-flex;align-items:center;justify-content:center;
    box-shadow:0 6px 16px rgba(0,0,0,.15);cursor:pointer;
  }
  .btn-heart:hover{ transform:translateY(-1px); }

  /* Ícone do coração (um único ícone sólido) */
  .btn-heart .icon-heart{
    font-size:18px;
    transition: color .2s ease, transform .15s ease, text-shadow .2s ease;
  }

  /* NÃO favoritado: parece “contorno” (sem preenchimento) */
  .btn-heart:not([aria-pressed="true"]) .icon-heart{
    color: transparent;                /* sem preenchimento */
    -webkit-text-stroke: 2px #999;     /* contorno (Chromium/Safari) */
    text-shadow: 0 0 0 #999;           /* fallback simples (Firefox) */
  }

  /* Favoritado: preenchido totalmente em vermelho */
  .btn-heart[aria-pressed="true"] .icon-heart{
    color: #d10000;
    -webkit-text-stroke: 0;
    text-shadow: none;
  }
</style>
@endpush

@push('scripts')
<script>
(function(){
  var STORAGE_KEY = 'favoritos';

  function readFavs(){
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); }
    catch(e){ return []; }
  }
  function writeFavs(list){
    localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
    try {
      window.dispatchEvent(new CustomEvent('fav:list-updated', { detail: { count: list.length, list: list }}));
    } catch(_) {}
  }
  function isFav(list, idStr){
    return list.some(function(x){ return String(x.id) === idStr; });
  }

  function cssEscapeCompat(s){
    if (window.CSS && typeof CSS.escape === 'function') return CSS.escape(s);
    return String(s).replace(/"/g, '\\"');
  }
  function setPressedForId(idStr, pressed){
    var sel = '.js-fav-toggle[data-id="' + cssEscapeCompat(idStr) + '"]';
    document.querySelectorAll(sel).forEach(function(btn){
      btn.setAttribute('aria-pressed', pressed ? 'true' : 'false');
    });
  }
  function syncAllHearts(){
    var favs = readFavs();
    document.querySelectorAll('.js-fav-toggle').forEach(function(btn){
      var idStr = String(btn.dataset.id || '');
      if (!idStr) return;
      btn.setAttribute('aria-pressed', isFav(favs, idStr) ? 'true' : 'false');
    });
  }

  // Clique no coração
  document.addEventListener('click', function(ev){
    var btn = ev.target.closest('.js-fav-toggle');
    if (!btn) return;

    ev.preventDefault();
    ev.stopPropagation();

    var idStr = String(btn.dataset.id || '');
    if (!idStr) return;

    var favs = readFavs();
    if (isFav(favs, idStr)) {
      favs = favs.filter(function(x){ return String(x.id) !== idStr; });
      setPressedForId(idStr, false);
    } else {
      favs.push({
        id: idStr,
        title: btn.dataset.title || '',
        preco: btn.dataset.precoFormatado || btn.dataset.preco_formatado || '',
        negocio: btn.dataset.negocio || '',
        cidade: btn.dataset.cidade || '',
        bairro: btn.dataset.bairro || '',
        quartos: btn.dataset.quartos || '',
        suites: btn.dataset.suites || '',
        banheiros: btn.dataset.banheiros || '',
        garagem: btn.dataset.garagem || '',
        area: btn.dataset.area || '',
        img: btn.dataset.img || '',
        link: btn.dataset.link || '',
        addedAt: Date.now()
      });
      setPressedForId(idStr, true);
    }

    writeFavs(favs); // dispara fav:list-updated
  });

  // Sincroniza no load e sempre que a lista mudar
  document.addEventListener('DOMContentLoaded', syncAllHearts);
  window.addEventListener('fav:list-updated', syncAllHearts);
})();
</script>
@endpush
@endonce
