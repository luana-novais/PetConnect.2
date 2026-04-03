<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - @yield('title', 'Painel')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS Global PetConnect -->
    <link href="{{ asset('app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand d-flex align-items-center gap-2">
            <span class="logo-icon">🐾</span>
            <span class="logo-text">Pet<em>Connect</em></span>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-label">Principal</div>
            <a href="#" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            <div class="sidebar-label mt-2">Gestão</div>
            <a href="{{ route('pets.index') }}" class="nav-link {{ request()->is('pets*') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span class="nav-text">Pets</span>
            </a>
            <a href="#" class="nav-link {{ request()->is('agendamentos*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                <span class="nav-text">Agendamentos</span>
            </a>
            <a href="#" class="nav-link {{ request()->is('servicos*') ? 'active' : '' }}">
                <i class="bi bi-scissors"></i>
                <span class="nav-text">Serviços</span>
            </a>
            <a href="#" class="nav-link {{ request()->is('pagamentos*') ? 'active' : '' }}">
                <i class="bi bi-credit-card"></i>
                <span class="nav-text">Pagamentos</span>
            </a>

            <div class="sidebar-label mt-2">Cadastros</div>
            <a href="#" class="nav-link">
                <i class="bi bi-people"></i>
                <span class="nav-text">Usuários</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-tag"></i>
                <span class="nav-text">Espécies & Raças</span>
            </a>
        </nav>

        <!-- Rodapé do usuário -->
        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2">
                <div class="sidebar-user-avatar flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="nav-text" style="overflow:hidden;">
                    <div class="text-white fw-semibold" style="font-size:0.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ auth()->user()->name ?? 'Usuário' }}
                    </div>
                    <div style="font-size:0.72rem;color:#78716C;">
                        {{ auth()->user()->role ?? 'CLI' }}
                    </div>
                </div>
            </div>
        </div>

    </aside>

    <!-- Main -->
    <div class="main-wrapper" id="mainWrapper">

        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <!-- Botão de recolher -->
                <button class="btn btn-sm btn-outline-secondary" id="sidebarToggle" title="Recolher menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h6 class="page-title">@yield('page-title', 'Painel')</h6>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-decoration-none" style="color:var(--pet-primary)">Home</a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-secondary position-relative">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">3</span>
                </a>
                
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar     = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggle      = document.getElementById('sidebarToggle');

        // Recupera estado salvo
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            mainWrapper.classList.add('sidebar-collapsed');
        }

        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    </script>

    @stack('scripts')
</body>
</html>