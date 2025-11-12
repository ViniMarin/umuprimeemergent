<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Entrar - UmuPrime Imóveis')</title>

  <!-- Bootstrap + Poppins + Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>

  <style>
    :root{
      --primary:#FFD700; /* amarelo UmuPrime */
      --dark-0:#0b0f14;
      --dark-1:#11161d;
      --card:#1b2028;
      --muted:#bac1ca;
      --line:#2a3038;
      --line-hover:#323843;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family:'Poppins',sans-serif; color:#fff;

      /* Luz só nos cantos (ampla) */
      background:
        radial-gradient(1400px 1000px at 115% -12%, rgba(255,215,0,.22), rgba(255,215,0,0) 65%),
        radial-gradient(1500px 1100px at -15% 115%, rgba(255,215,0,.20), rgba(255,215,0,0) 62%),
        linear-gradient(165deg, var(--dark-1) 0%, var(--dark-0) 66%, #0a0d12 100%);
      background-attachment: fixed;
    }

    .auth-wrap{min-height:100%; display:grid; place-items:center; padding:36px 16px}

    .auth-card{
      width:100%; max-width:520px;
      background: var(--card);
      border:1px solid rgba(255,215,0,.14);
      border-radius:18px;
      box-shadow:
        0 26px 80px rgba(0,0,0,.55),
        0 0 0 1px rgba(255,255,255,.04) inset;
      padding:22px 22px 26px;
    }

    /* ===== Logo com halo mais suave ===== */
    .auth-card-header{
      position:relative;
      display:flex; justify-content:center; align-items:center;
      padding:12px 0 16px 0; margin-bottom:8px;
      border-bottom:1px solid rgba(255,255,255,.06);
    }
    .auth-card-header::before{
      content:"";
      position:absolute; left:50%; top:50%;
      transform:translate(-50%,-52%);
      width:340px; height:120px; border-radius:50%;
      background: radial-gradient(closest-side,
                  rgba(255,215,0,.18) 0%,   /* ↓ opacidades reduzidas */
                  rgba(255,215,0,.08) 55%,
                  rgba(255,215,0,.03) 78%,
                  rgba(255,215,0,0) 100%);
      filter: blur(12px);
      z-index:0; pointer-events:none;
    }
    .auth-card-header::after{
      content:"";
      position:absolute; left:50%; top:50%;
      transform:translate(-50%,-56%);
      width:180px; height:68px; border-radius:50%;
      background: radial-gradient(closest-side,
                  rgba(255,215,0,.12) 0%,    /* núcleo mais discreto */
                  rgba(255,215,0,0) 100%);
      filter: blur(8px);
      z-index:0; pointer-events:none;
    }
    .auth-card-header img{
      height:62px; width:auto; pointer-events:none;
      z-index:1;
      filter: drop-shadow(0 4px 12px rgba(255,215,0,.18));
    }

    .auth-card-main{padding-top:10px}

    .auth-title{font-size:1.35rem; font-weight:700; margin-bottom:6px}
    .auth-sub{font-size:.95rem; color:var(--muted); margin-bottom:22px}

    /* ===== Inputs com borda e foco mais discretos ===== */
    .form-label{color:#f1f1f1; font-weight:500}
    .form-control{
      background:#141921;
      border:1.2px solid var(--line);   /* borda mais fina */
      color:#fff;
      transition:border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .form-control::placeholder{color:#98a0aa}
    .form-control:hover{
      border-color:var(--line-hover);   /* hover sutil */
    }
    .form-control:focus{
      outline:0;
      border-color:var(--primary);
      /* glow bem menor */
      box-shadow:0 0 0 .10rem rgba(255,215,0,.18);
      background:#141a23; color:#fff;
    }
    .input-group-text{
      background:#141921; border:1.2px solid var(--line); color:#cdd3da;
      transition:border-color .15s ease;
    }
    .input-group:hover .input-group-text{
      border-color:var(--line-hover);
    }

    .form-check-label{color:#d9dde3}

    .btn-primary{
      --bs-btn-bg: var(--primary);
      --bs-btn-border-color: var(--primary);
      --bs-btn-hover-bg: #ffc400;
      --bs-btn-hover-border-color: #ffc400;
      --bs-btn-color: #000;
      font-weight:700; border-radius:10px; padding:.7rem 1.1rem;
      box-shadow:none; /* sem luz no botão */
    }

    .text-link{color:var(--primary); text-decoration:none}
    .text-link:hover{color:#e6c500; text-decoration:underline}

    @media (max-width:420px){
      .auth-card{padding:18px}
      .auth-card-header img{height:56px}
      .auth-card-header::before{width:300px; height:110px}
      .auth-card-header::after{width:160px; height:60px}
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="auth-wrap">
    <div class="auth-card">
      <div class="auth-card-header">
        <img src="{{ asset('images/logo.png') }}" alt="UmuPrime Imóveis">
      </div>

      <div class="auth-card-main">
        @yield('content')
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
