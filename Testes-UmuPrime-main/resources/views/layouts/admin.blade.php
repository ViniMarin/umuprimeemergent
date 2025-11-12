<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Painel Administrativo - UmuPrime Imóveis')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #FFD700;
            --secondary-color: #000000;
            --accent-color: #FFA500;
            --text-dark: #333333;
            --text-light: #666666;
            --bg-light: #f8f9fa;
            --sidebar-width: 250px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }

        /* Sidebar */
        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--secondary-color), #333); color: white;
            z-index: 1000; transition: all 0.3s ease;
        }
        .sidebar-header { padding: 20px; border-bottom: 1px solid #444; text-align: center; }
        .sidebar-header img { height: 40px; filter: brightness(0) invert(1); }
        .sidebar-menu { padding: 20px 0; }
        .sidebar-menu .nav-link {
            color: #ccc; padding: 12px 20px; display: flex; align-items: center; text-decoration: none;
            transition: all 0.3s ease; border: none; background: none;
        }
        .sidebar-menu .nav-link:hover, .sidebar-menu .nav-link.active {
            background-color: var(--primary-color); color: var(--secondary-color); font-weight: 600;
        }
        .sidebar-menu .nav-link i { width: 20px; margin-right: 10px; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }

        /* Top Bar */
        .top-bar {
            background: white; padding: 15px 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex; justify-content: space-between; align-items: center;
        }
        .top-bar h1 { margin: 0; color: var(--text-dark); font-size: 24px; font-weight: 600; }

        /* Content Area */
        .content-area { padding: 30px; }

        /* Cards básicos (mantidos) */
        .stat-card {
            background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease; border-left: 4px solid var(--primary-color);
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card .stat-icon {
            width: 60px; height: 60px; background: var(--primary-color); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; color: var(--secondary-color);
            font-size: 24px; margin-bottom: 15px;
        }
        .stat-card .stat-number { font-size: 32px; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
        .stat-card .stat-label { color: var(--text-light); font-size: 14px; }

        /* Tables */
        .table-card { background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); overflow: hidden; }
        .table-card .card-header { background: var(--primary-color); color: var(--secondary-color); padding: 20px; font-weight: 600; border: none; }
        .table-responsive { border-radius: 0 0 15px 15px; }
        .table th { background-color: #f8f9fa; border: none; font-weight: 600; color: var(--text-dark); padding: 15px; }
        .table td { border: none; padding: 15px; vertical-align: middle; }
        .table tbody tr { border-bottom: 1px solid #eee; }
        .table tbody tr:hover { background-color: #f8f9fa; }

        /* Buttons base */
        .btn-primary {
            background-color: var(--primary-color); border-color: var(--primary-color);
            color: var(--secondary-color); font-weight: 600; border-radius: 8px; padding: 10px 20px;
        }
        .btn-primary:hover { background-color: var(--accent-color); border-color: var(--accent-color); color: var(--secondary-color); }
        .btn-outline-primary { border-color: var(--primary-color); color: var(--primary-color); }
        .btn-outline-primary:hover { background-color: var(--primary-color); color: var(--secondary-color); }

        /* Forms */
        .form-control, .form-select {
            border: 2px solid #e9ecef; border-radius: 8px; padding: 12px 15px; transition: border-color 0.3s ease;
        }
        .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25); }

        /* Alerts */
        .alert { border-radius: 10px; border: none; padding: 15px 20px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger  { background-color: #f8d7da; color: #721c24; }

        /* Image Preview */
        .image-preview { width: 80px; height: 60px; object-fit: cover; border-radius: 8px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .content-area { padding: 20px 15px; }
        }
    </style>

    @stack('styles')

    {{-- Remove DEFINITIVAMENTE o contorno azul dos botões/links --}}
    <style id="kill-blue-outline">
        /* Neutraliza o ring do Bootstrap nos botões */
        .btn,
        .btn-primary,
        .btn-outline-primary,
        .btn-secondary,
        .btn-outline-secondary,
        .btn-link {
            --bs-btn-focus-box-shadow: none !important;
            --bs-btn-focus-shadow-rgb: 0,0,0 !important;
        }

        /* Remove outline/box-shadow em todos os estados comuns */
        .btn,
        .btn:hover,
        .btn:focus,
        .btn:active,
        .btn:focus-visible,
        .btn:active:focus,
        .btn-check:focus + .btn,
        .btn-check:active + .btn,
        a.btn,
        a.btn:hover,
        a.btn:focus,
        a.btn:active,
        a.btn:focus-visible,
        .nav-link:focus,
        .nav-link:focus-visible,
        .page-link:focus,
        .page-link:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Garante que nada reapareça por :focus/:focus-visible globais */
        *:focus,
        *:focus-visible {
            outline: none !important;
            box-shadow: none !important;
        }

        /* Corrige outline buttons ao focar/hover */
        .btn-outline-primary:where(:hover,:focus,:active,:focus-visible){
            border-color: var(--primary-color) !important;
            color: var(--secondary-color) !important;
            background: var(--primary-color) !important;
        }

        /* Remove highlight azul no mobile */
        a, button { -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="UmuPrime">
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            <a href="{{ route('admin.imoveis.index') }}" class="nav-link {{ request()->routeIs('admin.imoveis.*') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Imóveis
            </a>

            <a href="{{ route('admin.imoveis.create') }}" class="nav-link">
                <i class="fas fa-plus"></i> Novo Imóvel
            </a>

            @can('admin')
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> Usuários
                </a>

                <a href="{{ route('admin.settings.home.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.home.*') ? 'active' : '' }}">
                    <i class="fas fa-image"></i> Banner da Home
                </a>
            @endcan

            <hr class="my-3" style="border-color: #444;">

            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt"></i> Ver Site
            </a>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="nav-link w-100 text-start">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link d-md-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>
            <div></div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Evita foco visual após clique com mouse (preserva foco via teclado)
        document.addEventListener('mousedown', function (e) {
            const el = e.target.closest('.btn, .nav-link, .page-link');
            if (el) setTimeout(() => el.blur(), 0);
        }, true);

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
