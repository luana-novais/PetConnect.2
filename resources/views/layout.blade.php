<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - @yield('title', 'Painel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    @php $role = auth()->user()->role ?? 'CLI'; @endphp

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand d-flex align-items-center gap-2">
            <span class="logo-icon">🐾</span>
            <span class="logo-text">Pet<em>Connect</em></span>
        </div>

        <nav class="sidebar-nav">

            {{-- ── Principal (todos) ── --}}
            <div class="sidebar-label">Principal</div>
            <a href="{{ route('welcome') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span class="nav-text">Dashboard</span>
            </a>

            {{-- ── ADM ── --}}
            @if($role === 'ADM')
                <div class="sidebar-label mt-2">Gestão</div>
                <a href="{{ route('agendamentos.index') }}" class="nav-link {{ request()->is('agendamentos*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>
                    <span class="nav-text">Agendamentos</span>
                </a>
                <a href="{{ route('servicos.index') }}" class="nav-link {{ request()->is('servicos*') ? 'active' : '' }}">
                    <i class="bi bi-scissors"></i>
                    <span class="nav-text">Serviços</span>
                </a>
                <a href="{{ route('disponibilidades.index') }}" class="nav-link {{ request()->is('disponibilidades*') ? 'active' : '' }}">
                    <i class="bi bi-clock"></i>
                    <span class="nav-text">Disponibilidade</span>
                </a>
                <a href="{{ route('pagamentos.index') }}" class="nav-link {{ request()->is('pagamentos*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span class="nav-text">Pagamentos</span>
                </a>

                <div class="sidebar-label mt-2">Cadastros</div>
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span class="nav-text">Usuários</span>
                </a>
                <a href="{{ route('pets.index') }}" class="nav-link {{ request()->is('pets*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i>
                    <span class="nav-text">Pets</span>
                </a>
                <a href="{{ route('especies.index') }}" class="nav-link {{ request()->is('especies*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span class="nav-text">Espécies</span>
                </a>
                <a href="{{ route('racas.index') }}" class="nav-link {{ request()->is('racas*') ? 'active' : '' }}">
                    <i class="bi bi-tag"></i>
                    <span class="nav-text">Raças</span>
                </a>
            @endif

            {{-- ── PRO ── --}}
            @if($role === 'PRO')
                <div class="sidebar-label mt-2">Meu trabalho</div>
                <a href="{{ route('agendamentos.index') }}" class="nav-link {{ request()->is('agendamentos*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>
                    <span class="nav-text">Agendamentos</span>
                </a>
                <a href="{{ route('servicos.index') }}" class="nav-link {{ request()->is('servicos*') ? 'active' : '' }}">
                    <i class="bi bi-scissors"></i>
                    <span class="nav-text">Serviços</span>
                </a>
                <a href="{{ route('disponibilidades.index') }}" class="nav-link {{ request()->is('disponibilidades*') ? 'active' : '' }}">
                    <i class="bi bi-clock"></i>
                    <span class="nav-text">Disponibilidade</span>
                </a>
                <a href="{{ route('pagamentos.index') }}" class="nav-link {{ request()->is('pagamentos*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span class="nav-text">Pagamentos</span>
                </a>

                <div class="sidebar-label mt-2">Conta</div>
                <a href="{{ route('users.edit', auth()->id()) }}" class="nav-link {{ request()->is('users/'.auth()->id().'/edit') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span class="nav-text">Meu Perfil</span>
                </a>
            @endif

            {{-- ── CLI ── --}}
            @if($role === 'CLI')
                <div class="sidebar-label mt-2">Minha área</div>
                <a href="{{ route('pets.index') }}" class="nav-link {{ request()->is('pets*') ? 'active' : '' }}">
                    <i class="bi bi-heart"></i>
                    <span class="nav-text">Meus Pets</span>
                </a>
                <a href="{{ route('agendamentos.index') }}" class="nav-link {{ request()->is('agendamentos*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i>
                    <span class="nav-text">Agendamentos</span>
                </a>
                <a href="{{ route('pagamentos.index') }}" class="nav-link {{ request()->is('pagamentos*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i>
                    <span class="nav-text">Pagamentos</span>
                </a>

                <div class="sidebar-label mt-2">Conta</div>
                <a href="{{ route('users.edit', auth()->id()) }}" class="nav-link {{ request()->is('users/'.auth()->id().'/edit') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i>
                    <span class="nav-text">Meu Perfil</span>
                </a>
            @endif

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
                        @if($role === 'ADM') Administrador
                        @elseif($role === 'PRO') Profissional
                        @else Cliente
                        @endif
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
                <button class="btn btn-sm btn-outline-secondary" id="sidebarToggle" title="Recolher menu">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h6 class="page-title">@yield('page-title', 'Painel')</h6>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('welcome') }}" class="text-decoration-none" style="color:var(--pet-primary)">Home</a>
                            </li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                @auth
                    <span class="text-secondary small">Olá, {{ auth()->user()->name }}</span>
                    <form action="{{ route('auth.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </button>
                    </form>
                @endauth
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar     = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggle      = document.getElementById('sidebarToggle');

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