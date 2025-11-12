@push('scripts')
<script>
(function(){
  var KEY='favoritos';

  function getFavs(){
    try { return JSON.parse(localStorage.getItem(KEY) || '[]'); }
    catch(e){ return []; }
  }
  function setFavs(list){
    localStorage.setItem(KEY, JSON.stringify(list));
    try {
      window.dispatchEvent(new CustomEvent('fav:list-updated', { detail: { count: list.length, list: list }}));
    } catch(_) {}
  }
  function inList(id){ return getFavs().some(function(x){ return String(x.id) === String(id); }); }

  function cssEscapeCompat(s){
    if (window.CSS && typeof CSS.escape === 'function') return CSS.escape(s);
    return String(s).replace(/"/g, '\\"');
  }
  function esc(s){
    return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;', "'":'&#39;'}[m]));
  }

  // Atualiza contadores (bolinhas, badges…)
  function renderCount(){
    var c = getFavs().length;
    document.querySelectorAll('[data-fav-count]').forEach(function(el){ el.textContent = c; });
  }

  // Remove/oculta linhas que não estão mais na lista
  function syncExistingRows(){
    var ids = new Set(getFavs().map(x => String(x.id)));
    document.querySelectorAll('[data-fav-row]').forEach(function(row){
      var id = String(row.dataset.id || '');
      if (!ids.has(id)) row.remove();
    });
  }

  // (Opcional) Auto-render da lista
  function rowHTML(item){
    return `<div class="fav-row d-flex align-items-center justify-content-between border-bottom py-2" data-fav-row data-id="${esc(item.id)}">
      <div class="d-flex align-items-center gap-2">
        <img src="${esc(item.img||'')}" alt="" style="width:64px;height:44px;object-fit:cover;border-radius:6px;">
        <div>
          <a href="${esc(item.link||'#')}" class="fw-semibold text-decoration-none">${esc(item.title||'Imóvel')}</a>
          <div class="small text-muted">${esc(item.bairro||'')} - ${esc(item.cidade||'')}</div>
        </div>
      </div>
      <div class="text-end">
        <div class="fw-bold">${esc(item.preco||'')}</div>
        <button type="button" class="btn btn-outline-danger btn-sm js-fav-remove" data-id="${esc(item.id)}" title="Remover">
          <i class="fa fa-trash"></i>
        </button>
      </div>
    </div>`;
  }
  function renderListIfNeeded(){
    var wrap = document.querySelector('[data-fav-list][data-fav-auto-render="1"]');
    if (!wrap) return;
    var list = getFavs();
    wrap.innerHTML = list.length
      ? list.map(rowHTML).join('')
      : '<div class="text-muted p-3">Nenhum favorito ainda.</div>';
  }

  // Ações de remover dentro da aba
  document.addEventListener('click', function(e){
    var rm = e.target.closest('.js-fav-remove');
    if (!rm) return;

    e.preventDefault();
    e.stopPropagation();

    var id = String(rm.dataset.id || '');
    var list = getFavs().filter(function(x){ return String(x.id) !== id; });
    setFavs(list); // dispara fav:list-updated

    // desmarca corações desse ID
    document.querySelectorAll('.js-fav-toggle[data-id="' + cssEscapeCompat(id) + '"]')
      .forEach(function(btn){ btn.setAttribute('aria-pressed','false'); });

    // atualiza UI local
    renderCount();
    syncExistingRows();
    renderListIfNeeded();
  });

  function init(){
    renderCount();
    syncExistingRows();
    renderListIfNeeded();
  }

  document.addEventListener('DOMContentLoaded', init);
  window.addEventListener('fav:list-updated', init);
})();
</script>
@endpush
