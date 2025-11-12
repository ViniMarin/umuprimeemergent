<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="UmuPrime Imóveis - Sua imobiliária de confiança em Umuarama">
    <title>@yield('title', 'UmuPrime Imóveis - Sua casa dos sonhos está aqui')</title>

    <!-- Bootstrap / Font Awesome / Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-color:#FFD700; --secondary-color:#000; --accent-color:#FFA500; --text-dark:#333; --text-light:#666; --bg-light:#f8f9fa; }
        *{box-sizing:border-box}
        body{font-family:'Poppins',sans-serif;color:var(--text-dark);line-height:1.6}

        /* Topo Instagram */
        .header-top{background:var(--secondary-color);color:#fff;padding:8px 0;font-size:14px}
        .ig-wrap{display:flex;justify-content:center}
        .ig-pill{display:inline-flex;align-items:center;gap:8px;padding:6px 14px;border:1px solid rgba(255,255,255,.25);border-radius:999px;background:rgba(255,255,255,.06);color:#fff;text-decoration:none;transition:.25s}
        .ig-pill:hover{background:rgba(255,255,255,.15);transform:translateY(-1px);color:#fff}

        /* ======= NAVBAR ======= */
        .navbar.site-navbar{background:#fff !important;box-shadow:0 2px 10px rgba(0,0,0,.1);padding:15px 0}
        .site-navbar .container-nav{max-width:1400px;padding-left:10px;padding-right:10px}
        .navbar-brand{margin-right:.5rem}
        .navbar-brand img{height:50px}
        .site-navbar .navbar-collapse{justify-content:flex-end}
        .site-navbar .navbar-nav{align-items:center;gap:.5rem}
        @media (min-width:992px){
            .site-navbar .navbar-nav{gap:1.25rem}
            .navbar-brand{margin-right:1rem}
            .site-navbar .container-nav{padding-left:8px;padding-right:8px}
        }
        .navbar-nav .nav-link{color:var(--text-dark)!important;font-weight:500;margin:0}
        .navbar-nav .nav-link:hover{color:var(--primary-color)!important}

        .fav-link{position:relative}
        .fav-badge{position:absolute;top:-8px;right:-10px;min-width:18px;height:18px;border-radius:9px;background:#dc3545;color:#fff;font-size:11px;line-height:18px;text-align:center;padding:0 4px;font-weight:700}

        /* Botão de coração (cards / detalhe) */
        .btn-heart{border:none;background:rgba(255,255,255,.9);width:36px;height:36px;border-radius:50%;display:grid;place-items:center;box-shadow:0 2px 8px rgba(0,0,0,.15);transition:.15s}
        .btn-heart:hover{transform:scale(1.05)}
        .btn-heart i{color:#bbb}
        .btn-heart.is-fav i{color:#e53935}

        /* Offcanvas favoritos */
        .fav-thumb{width:64px;height:48px;object-fit:cover;border-radius:8px}
        .fav-item-title{font-weight:600}
        .fav-item-meta{font-size:12px;color:#777}
        .offcanvas-header .badge{font-weight:600}

        /* Preço e badge (empilhados) */
        .fav-price-block{display:flex;flex-direction:column;align-items:flex-start}
        .price-pill{white-space:nowrap;word-break:keep-all;display:inline-block;line-height:1.1;font-weight:800;font-size:20px;color:#000}
        .deal-badge{font-size:12px;font-weight:700;padding:4px 8px;border-radius:999px;background:#ffd500;color:#000}

        /* Botão amarelo */
        .btn-warning, .btn-warning:hover, .btn-warning:focus{
            background:var(--primary-color);
            border-color:var(--primary-color);
            color:var(--secondary-color);
        }

        /* ====== COMPARAÇÃO (grade horizontal por coluna) ====== */
        .compare-grid{
            display:grid;
            gap:16px;
            grid-auto-flow:column;
            grid-auto-columns:340px;
            overflow-x:auto;
            padding:8px;
            scroll-snap-type:x proximity;
            -webkit-overflow-scrolling:touch;
            cursor:grab;
        }
        .compare-grid.dragging{cursor:grabbing}
        .compare-grid::-webkit-scrollbar{height:10px}
        .compare-grid::-webkit-scrollbar-thumb{background:#ddd;border-radius:6px}

        .compare-card-full{scroll-snap-align:start}
        .compare-card-full .compare-thumb{width:100%;height:160px;object-fit:cover;border-radius:10px}
        .compare-card-full .card{height:auto !important}
        .compare-card-full .cmp-table{border:1px solid #eee;border-radius:10px;overflow:hidden}
        .compare-card-full .cmp-row{display:flex;justify-content:space-between;gap:12px;padding:8px 12px;border-bottom:1px solid #f1f1f1}
        .compare-card-full .cmp-row:last-child{border-bottom:none}
        .compare-card-full .cmp-label{color:#6c757d;font-weight:600}
        .compare-card-full .cmp-value{color:#212529}
        .compare-title{font-weight:600;margin:8px 0 2px}
        .compare-loc{font-size:.85rem;color:#6c757d}
        .compare-price{display:flex;align-items:center;gap:8px;margin-top:8px}

        /* Hero / Busca / Cards / WhatsApp */
        .hero-section{
            background:
              linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)),
              url('{{ \App\Models\SiteSetting::singleton()->hero_image_url }}');
            background-size:cover;background-position:center;min-height:70vh;display:flex;align-items:center;color:#fff;text-align:center
        }
        .hero-content h1{font-size:3.5rem;font-weight:700;margin-bottom:20px;text-shadow:2px 2px 4px rgba(0,0,0,.5)}
        .hero-content p{font-size:1.3rem;margin-bottom:30px;text-shadow:1px 1px 2px rgba(0,0,0,.5)}

        .search-form{background:#fff;padding:30px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,.1);margin-top:-50px;position:relative;z-index:10}
        .search-form .form-control,.search-form .form-select{border:2px solid #e9ecef;border-radius:8px;padding:12px 15px}
        .search-form .form-control:focus,.search-form .form-select:focus{border-color:var(--primary-color);box-shadow:0 0 0 .2rem rgba(255,215,0,.25)}

        .btn-primary{background:var(--primary-color);border-color:var(--primary-color);color:var(--secondary-color);font-weight:600;padding:12px 30px;border-radius:8px}
        .btn-primary:hover{background:var(--accent-color);border-color:var(--accent-color);transform:translateY(-2px)}

        .property-card{background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 5px 20px rgba(0,0,0,.1);transition:.3s;margin-bottom:30px}
        .property-card:hover{transform:translateY(-5px);box-shadow:0 10px 30px rgba(0,0,0,.15)}
        .property-image{height:250px;background-size:cover;background-position:center;position:relative}
        .property-badge{position:absolute;top:15px;left:15px;background:var(--primary-color);color:var(--secondary-color);padding:5px 12px;border-radius:20px;font-size:12px;font-weight:600}
        .property-price{position:absolute;bottom:15px;right:15px;background:rgba(0,0,0,.8);color:#fff;padding:8px 15px;border-radius:20px;font-weight:600}
        .property-info{padding:20px}
        .property-title{font-size:18px;font-weight:600;margin-bottom:10px;color:var(--text-dark)}
        .property-location{color:var(--text-light);font-size:14px;margin-bottom:15px}
        .property-features{display:flex;gap:15px;margin-bottom:15px}
        .feature-item{display:flex;align-items:center;gap:5px;font-size:14px;color:var(--text-light)}

        .whatsapp-float{
            position:fixed;width:60px;height:60px;bottom:40px;right:40px;background:#25d366;color:#fff;
            border-radius:50px;text-align:center;font-size:30px;box-shadow:2px 2px 3px #999;z-index:100;
            display:flex;align-items:center;justify-content:center;text-decoration:none
        }
        .whatsapp-float:hover{background:#128c7e;color:#fff;transform:scale(1.1)}

        /* ===== Footer ===== */
        .footer{background:var(--secondary-color);color:#fff;padding:50px 0 20px;margin-top:80px}
        .footer h5{color:var(--primary-color);margin-bottom:20px}
        .footer a{color:#ccc;text-decoration:none}
        .footer a:hover{color:var(--primary-color)}
        /* Ícones das redes: com gap próprio e respiro inferior */
        .social-links{
          display:flex;
          gap:12px;
          margin:12px 0 18px;
        }
        .social-links a{
          display:inline-block;width:40px;height:40px;
          background:var(--primary-color);color:var(--secondary-color);
          text-align:center;line-height:40px;border-radius:50%;
          transition:.3s;margin-right:0;
        }
        .social-links a:hover{background:var(--accent-color);transform:translateY(-3px)}
        /* Espaço entre blocos do rodapé quando empilha */
        .footer .row > [class*="col-"]{margin-bottom:24px}
        @media (min-width:768px){
          .footer .row > [class*="col-"]{margin-bottom:0}
        }

        @media (max-width:768px){
            .hero-content h1{font-size:2.5rem}
            .search-form{margin:20px;padding:20px}
            .whatsapp-float{width:50px;height:50px;bottom:20px;right:20px;font-size:24px}
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Top: Instagram -->
    <div class="header-top">
        <div class="container">
            <div class="ig-wrap">
                <a class="ig-pill" href="https://www.instagram.com/umuprimeimoveis" target="_blank">
                    <i class="fab fa-instagram"></i><span>@umuprimeimoveis</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light site-navbar">
        <div class="container container-nav">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="UmuPrime Imóveis">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Abrir menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('sobre') }}">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('imoveis.aluguel') }}">Imóveis para Alugar</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('imoveis.venda') }}">Imóveis à Venda</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contato') }}">Contato</a></li>

                    <!-- Favoritos -->
                    <li class="nav-item">
                        <a class="nav-link fav-link" data-bs-toggle="offcanvas" href="#favOffcanvas" role="button">
                            <i class="fa-solid fa-heart me-1"></i> Favoritos
                            <span id="favCount" class="fav-badge">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo -->
    <main>@yield('content')</main>

    <!-- Offcanvas Favoritos -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="favOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">
                <i class="fa-solid fa-heart text-danger me-2"></i> Favoritos
                <span class="badge bg-secondary ms-2" id="favCountHeader">0</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div id="favEmpty" class="text-muted">Você ainda não favoritou nenhum imóvel.</div>
            <div id="favList" class="list-group list-group-flush"></div>
            <button id="btnOpenCompare" class="btn btn-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#compareModal" disabled>
                <i class="fa-solid fa-code-compare me-2"></i> Comparar Imóveis
            </button>
        </div>
    </div>

    <!-- Modal Comparação -->
    <div class="modal fade" id="compareModal" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Imóveis Favoritos (<span id="compareCount">0</span>)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div id="compareGrid" class="compare-grid"></div>
          </div>
          <div class="modal-footer"><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button></div>
        </div>
      </div>
    </div>

    <!-- WhatsApp -->
    <a href="https://wa.me/5544997292225?text=Olá! Gostaria de mais informações sobre os imóveis." class="whatsapp-float" target="_blank" aria-label="WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- adicionada margem nas colunas no mobile via CSS; se preferir, pode usar mb-4 mb-md-0 aqui -->
                <div class="col-md-4">
                    <h5>UmuPrime Imóveis</h5>
                    <p>Sua imobiliária de confiança em Umuarama. Encontre o imóvel dos seus sonhos conosco.</p>
                    <div class="social-links">
                        <a href="https://www.instagram.com/umuprimeimoveis" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.facebook.com/umuprime" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="https://wa.me/5544997292225" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Links Úteis</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Início</a></li>
                        <li><a href="{{ route('sobre') }}">Sobre</a></li>
                        <li><a href="{{ route('imoveis.aluguel') }}">Imóveis para Alugar</a></li>
                        <li><a href="{{ route('imoveis.venda') }}">Imóveis à Venda</a></li>
                        <li><a href="{{ route('contato') }}">Contato</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contato</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Umuarama - PR</p>
                    <p><i class="fas fa-phone"></i> (44) 99729-2225</p>
                    <p><i class="fas fa-envelope"></i> contato@umuprime.com.br</p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">&copy; {{ date('Y') }} UmuPrime Imóveis. Todos os direitos reservados.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    (function(){
        const KEY = 'umuprime_favorites_v1';

        const elFavCount = document.getElementById('favCount');
        const elFavCountHeader = document.getElementById('favCountHeader');
        const elFavList = document.getElementById('favList');
        const elFavEmpty = document.getElementById('favEmpty');
        const elBtnCompare = document.getElementById('btnOpenCompare');

        const elCompareGrid = document.getElementById('compareGrid');
        const elCompareCount = document.getElementById('compareCount');

        function getFavs(){ try{ return JSON.parse(localStorage.getItem(KEY))||[] }catch(e){ return [] } }
        function saveFavs(arr){ localStorage.setItem(KEY, JSON.stringify(arr)); renderFavUI(); markHearts(); }
        function isFav(id){ return getFavs().some(x => String(x.id)===String(id)); }

        function setHeart(btn,on){
            btn.classList.toggle('is-fav', on);
            btn.setAttribute('aria-pressed', on ? 'true' : 'false');
            btn.innerHTML = on
              ? '<i class="fa-solid fa-heart"></i>'
              : '<i class="fa-regular fa-heart"></i>';
        }

        function markHearts(){
            document.querySelectorAll('.js-fav-toggle').forEach(btn=>{
                setHeart(btn, isFav(btn.dataset.id));
            });
        }

        function renderFavUI(){
            const favs = getFavs(), n=favs.length;
            if(elFavCount) elFavCount.textContent = n;
            if(elFavCountHeader) elFavCountHeader.textContent = n;

            if(!elFavList) return;
            elFavList.innerHTML = '';
            if(n===0){
                if (elFavEmpty) elFavEmpty.style.display='block';
                if (elBtnCompare) elBtnCompare.disabled = true;
                return;
            }
            if (elFavEmpty) elFavEmpty.style.display='none';
            if (elBtnCompare) elBtnCompare.disabled = n<2;

            favs.forEach(f=>{
                const row=document.createElement('div');
                row.className='list-group-item d-flex align-items-center gap-3';
                row.innerHTML = `
                    <img src="${f.img||''}" class="fav-thumb" alt="">
                    <div class="flex-grow-1">
                        <div class="fav-item-title">${f.titulo||'Imóvel'}</div>
                        <div class="fav-item-meta">${f.bairro||''}${f.cidade?' - '+f.cidade:''}</div>
                        <div class="fav-price-block mt-1">
                            <span class="price-pill">${f.precoFormatado||''}</span>
                            ${f.negocio?`<span class="deal-badge mt-1">${(f.negocio||'').toUpperCase()}</span>`:''}
                        </div>
                    </div>
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <a href="${f.link||'#'}" target="_blank" class="btn btn-sm btn-warning" title="Ver imóvel">
                            <i class="fa-solid fa-up-right-from-square"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger js-remove-fav" data-id="${f.id}" title="Remover">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>`;
                elFavList.appendChild(row);
            });
        }

        function openCompare(){
            const favs=getFavs();
            if (elCompareCount) elCompareCount.textContent = favs.length;
            if (!elCompareGrid) return;
            elCompareGrid.innerHTML = '';

            const rows = [
                { key:'precoFormatado', label:'Preço' },
                { key:'negocioUp',     label:'Negócio' },
                { key:'cidade',        label:'Cidade' },
                { key:'bairro',        label:'Bairro' },
                { key:'quartos',       label:'Quartos' },
                { key:'suites',        label:'Suítes' },
                { key:'banheiros',     label:'Banheiros' },
                { key:'garagem',       label:'Garagem' },
                { key:'area',          label:'Área (m²)' },
            ];

            favs.forEach(f=>{
                const col = document.createElement('div');
                col.className = 'compare-card-full';

                const negocio = (f.negocio || '').toUpperCase();
                const loc = [f.bairro || '', f.cidade || ''].filter(Boolean).join(' - ');

                col.innerHTML = `
                    <div class="card">
                        <img src="${f.img||''}" class="compare-thumb" alt="">
                        <div class="card-body">
                            <div class="compare-price">
                                <span class="price-pill">${f.precoFormatado||''}</span>
                                ${negocio?`<span class="deal-badge">${negocio}</span>`:''}
                            </div>
                            <div class="compare-title">${f.titulo||''}</div>
                            <div class="compare-loc">${loc}</div>
                            <a href="${f.link||'#'}" target="_blank" class="btn btn-warning btn-sm mt-2">Ver imóvel</a>
                        </div>
                    </div>
                    <div class="cmp-table mt-3">
                        ${rows.map(r => `
                            <div class="cmp-row">
                                <div class="cmp-label">${r.label}</div>
                                <div class="cmp-value">${formatCell(r.key, f)}</div>
                            </div>
                        `).join('')}
                    </div>
                `;
                elCompareGrid.appendChild(col);
            });

            enableDragScroll(elCompareGrid);
        }

        function formatCell(key, f){
            if(key === 'negocioUp') return (f.negocio || '').toUpperCase();
            const v = f[key];
            return (v===null || v===undefined || v==='') ? '—' : v;
        }

        function enableDragScroll(el){
            let isDown=false, startX=0, scrollLeft=0;
            if(el.dataset.dragBound) return;
            el.dataset.dragBound = '1';

            el.addEventListener('mousedown', (e)=>{
                isDown=true; el.classList.add('dragging');
                startX = e.pageX - el.offsetLeft;
                scrollLeft = el.scrollLeft;
            });
            el.addEventListener('mouseleave', ()=>{ isDown=false; el.classList.remove('dragging'); });
            el.addEventListener('mouseup',   ()=>{ isDown=false; el.classList.remove('dragging'); });
            el.addEventListener('mousemove', (e)=>{
                if(!isDown) return;
                e.preventDefault();
                const x = e.pageX - el.offsetLeft;
                const walk = (x - startX);
                el.scrollLeft = scrollLeft - walk;
            });

            let tStartX=0, tScrollLeft=0;
            el.addEventListener('touchstart', (e)=>{
                const t = e.touches[0];
                tStartX = t.clientX; tScrollLeft = el.scrollLeft;
            }, {passive:true});
            el.addEventListener('touchmove', (e)=>{
                const t = e.touches[0];
                const walk = (t.clientX - tStartX);
                el.scrollLeft = tScrollLeft - walk;
            }, {passive:true});
        }

        document.addEventListener('click', function(e){
            const t = e.target.closest('.js-fav-toggle');
            if(t){
                e.preventDefault(); e.stopPropagation();
                const id = t.dataset.id;

                const willTurnOn = !isFav(id);
                setHeart(t, willTurnOn);

                const item = {
                    id,
                    titulo: t.dataset.title || t.dataset.titulo || '',
                    precoFormatado: t.dataset.precoFormatado || t.dataset.preco || '',
                    negocio: t.dataset.negocio || '',
                    cidade: t.dataset.cidade || '',
                    bairro: t.dataset.bairro || '',
                    quartos: t.dataset.quartos || '',
                    suites: t.dataset.suites || '',
                    banheiros: t.dataset.banheiros || '',
                    garagem: t.dataset.garagem || '',
                    area: t.dataset.area || '',
                    img: t.dataset.img || '',
                    link: t.dataset.link || ''
                };

                const favs = getFavs();
                if(willTurnOn){
                    if(!isFav(id)) favs.push(item);
                }else{
                    const i = favs.findIndex(x=>String(x.id)===String(id));
                    if(i>-1) favs.splice(i,1);
                }
                saveFavs(favs);
                return;
            }

            const del = e.target.closest('.js-remove-fav');
            if(del){
                e.preventDefault();
                const id = del.dataset.id;
                const favs = getFavs().filter(x=>String(x.id)!==String(id));
                saveFavs(favs);
                return;
            }
        });

        /* AQUI: só usamos a referência já criada no topo, sem redeclarar */
        elBtnCompare && elBtnCompare.addEventListener('click', openCompare);
        window.addEventListener('storage', ev => { if(ev.key===KEY){ renderFavUI(); markHearts(); } });

        renderFavUI(); markHearts();
        document.addEventListener('DOMContentLoaded', markHearts);
    })();
    </script>

    @stack('scripts')
</body>
</html>
