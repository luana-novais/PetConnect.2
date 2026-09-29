@extends('layout')

@section('title', 'Dashboard - PetConnect')
@section('page-title', 'Dashboard')

@push('styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@push('styles')
<style>
    .admin-dashboard .admin-hero {
        background: linear-gradient(135deg, #fff7ed 0%, #ffffff 70%);
        border: 1px solid #fed7aa;
    }
    .admin-dashboard .admin-stat {
        border: 1px solid #e7e5e4;
        border-radius: 14px;
        background: #fff;
        padding: 1.1rem;
        height: 100%;
    }
    .admin-dashboard .admin-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff7ed;
        color: #c2410c;
        font-size: 1.1rem;
    }
    .admin-dashboard .admin-stat-value {
        color: #1c1917;
        font-size: 1.55rem;
        font-weight: 800;
        line-height: 1;
    }
    .admin-dashboard .admin-stat-label {
        color: #78716c;
        font-size: .78rem;
    }
    .admin-dashboard .admin-section {
        border: 1px solid #e7e5e4;
        border-radius: 14px;
        background: #fff;
        overflow: hidden;
    }
    .admin-dashboard .admin-section-header {
        align-items: center;
        border-bottom: 1px solid #e7e5e4;
        display: flex;
        justify-content: space-between;
        padding: .95rem 1.1rem;
    }
    .admin-dashboard .admin-section-title {
        color: #292524;
        font-size: .92rem;
        font-weight: 700;
        margin: 0;
    }
    .admin-dashboard .admin-row {
        align-items: center;
        border-bottom: 1px solid #f5f5f4;
        display: flex;
        gap: .75rem;
        justify-content: space-between;
        padding: .85rem 1.1rem;
    }
    .admin-dashboard .admin-row:last-child { border-bottom: 0; }
    .admin-dashboard .admin-row-main { min-width: 0; }
    .admin-dashboard .admin-row-title {
        color: #292524;
        font-size: .84rem;
        font-weight: 700;
    }
    .admin-dashboard .admin-row-meta {
        color: #78716c;
        font-size: .76rem;
    }
    .admin-dashboard .admin-status {
        border-radius: 2rem;
        font-size: .72rem;
        font-weight: 700;
        padding: .3rem .55rem;
        white-space: nowrap;
    }
    .admin-dashboard .admin-status-pendente { background: #fef9c3; color: #854d0e; }
    .admin-dashboard .admin-status-confirmado { background: #dcfce7; color: #166534; }
    .admin-dashboard .admin-status-concluido { background: #dbeafe; color: #1e40af; }
    .admin-dashboard .admin-status-cancelado { background: #fee2e2; color: #991b1b; }
    .admin-dashboard .admin-action {
        align-items: center;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 8px;
        color: #c2410c;
        display: flex;
        gap: .45rem;
        padding: .65rem .8rem;
        text-decoration: none;
        font-size: .8rem;
        font-weight: 600;
    }
    .admin-dashboard .admin-action:hover { background: #ffedd5; color: #9a3412; }
    @media (max-width: 575.98px) {
        .admin-dashboard .admin-row { align-items: flex-start; flex-direction: column; }
        .admin-dashboard .admin-status { align-self: flex-start; }
    }
</style>
@endpush

@section('content')
@if(auth()->user()->role === 'ADM')
    @php
        $adminUserCount = \App\Models\User::count();
        $adminClientCount = \App\Models\User::where('role', 'CLI')->count();
        $adminSitterCount = \App\Models\User::where('role', 'PRO')->count();
        $adminPetCount = \App\Models\Pet::count();
        $adminServiceCount = \App\Models\Servico::count();
        $adminPendingCount = \App\Models\Agendamento::where('status', 'pendente')->count();
        $adminConfirmedCount = \App\Models\Agendamento::where('status', 'confirmado')->count();
        $adminCompletedCount = \App\Models\Agendamento::where('status', 'concluido')->count();
        $adminCanceledCount = \App\Models\Agendamento::where('status', 'cancelado')->count();
        $adminPaymentPendingCount = \App\Models\Pagamento::where('status_pagamento', 'pendente')->count();
        $adminPaidTotal = \App\Models\Pagamento::where('status_pagamento', 'pago')->sum('total_servico');
        $adminPlatformRating = \App\Models\Avaliacao::avg('nota');
        $adminUpcoming = \App\Models\Agendamento::with(['cliente', 'pet', 'sitter', 'servico'])
            ->whereIn('status', ['pendente', 'confirmado'])
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio')
            ->limit(5)
            ->get();
    @endphp

    <div class="admin-dashboard">
        <div class="admin-hero rounded-4 p-4 mb-4">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="text-muted small mb-1">Painel administrativo</div>
                    <h4 class="mb-2" style="color:#1C1917;">Visão geral da PetConnect</h4>
                    <p class="text-muted mb-0" style="font-size:.88rem;">Monitore a operação, a base de usuários e a saúde da plataforma.</p>
                </div>
                <i class="bi bi-speedometer2" style="font-size:2.2rem;color:#ea580c;"></i>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-people"></i></div>
                    <div class="admin-stat-value">{{ $adminUserCount }}</div>
                    <div class="admin-stat-label">Usuários cadastrados</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-person-badge"></i></div>
                    <div class="admin-stat-value">{{ $adminSitterCount }}</div>
                    <div class="admin-stat-label">Sitters ativos</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-briefcase"></i></div>
                    <div class="admin-stat-value">{{ $adminServiceCount }}</div>
                    <div class="admin-stat-label">Serviços cadastrados</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-heart"></i></div>
                    <div class="admin-stat-value">{{ $adminPetCount }}</div>
                    <div class="admin-stat-label">Pets cadastrados</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-hourglass-split"></i></div>
                    <div class="admin-stat-value">{{ $adminPendingCount }}</div>
                    <div class="admin-stat-label">Aguardando confirmação</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-check2-circle"></i></div>
                    <div class="admin-stat-value">{{ $adminCompletedCount }}</div>
                    <div class="admin-stat-label">Atendimentos concluídos</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-star"></i></div>
                    <div class="admin-stat-value">{{ $adminPlatformRating ? number_format($adminPlatformRating, 1, ',', '.') : '—' }}</div>
                    <div class="admin-stat-label">Média da plataforma</div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="admin-stat">
                    <div class="admin-stat-icon mb-3"><i class="bi bi-cash-stack"></i></div>
                    <div class="admin-stat-value" style="font-size:1.15rem;">R$ {{ number_format($adminPaidTotal, 2, ',', '.') }}</div>
                    <div class="admin-stat-label">Receita de pagamentos</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="admin-section">
                    <div class="admin-section-header">
                        <h6 class="admin-section-title"><i class="bi bi-activity me-2 text-warning"></i>Monitoramento operacional</h6>
                        <a href="{{ route('agendamentos.index') }}" class="small text-decoration-none">Ver todos</a>
                    </div>
                    @forelse($adminUpcoming as $agendamento)
                        <div class="admin-row">
                            <div class="admin-row-main">
                                <div class="admin-row-title">{{ $agendamento->pet->nome ?? 'Pet' }} · {{ $agendamento->servico->nome ?? 'Serviço' }}</div>
                                <div class="admin-row-meta">
                                    {{ $agendamento->data_inicio?->format('d/m/Y H:i') }} · Cliente: {{ $agendamento->cliente->name ?? '—' }} · Sitter: {{ $agendamento->sitter->name ?? '—' }}
                                </div>
                            </div>
                            <span class="admin-status admin-status-{{ $agendamento->status }}">{{ ucfirst($agendamento->status) }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted small">Nenhum próximo agendamento.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="admin-section h-100">
                    <div class="admin-section-header">
                        <h6 class="admin-section-title"><i class="bi bi-bar-chart me-2 text-warning"></i>Indicadores de controle</h6>
                    </div>
                    <div class="p-3">
                        <div class="admin-row px-0">
                            <span class="admin-row-meta">Clientes</span>
                            <strong>{{ $adminClientCount }}</strong>
                        </div>
                        <div class="admin-row px-0">
                            <span class="admin-row-meta">Agendamentos confirmados</span>
                            <strong>{{ $adminConfirmedCount }}</strong>
                        </div>
                        <div class="admin-row px-0">
                            <span class="admin-row-meta">Agendamentos cancelados</span>
                            <strong>{{ $adminCanceledCount }}</strong>
                        </div>
                        <div class="admin-row px-0">
                            <span class="admin-row-meta">Pagamentos pendentes</span>
                            <strong class="text-warning">{{ $adminPaymentPendingCount }}</strong>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('users.index') }}" class="admin-action"><i class="bi bi-people"></i>Gerenciar usuários</a>
                            <a href="{{ route('agendamentos.index') }}" class="admin-action"><i class="bi bi-calendar3"></i>Controlar agendamentos</a>
                            <a href="{{ route('pagamentos.index') }}" class="admin-action"><i class="bi bi-credit-card"></i>Acompanhar pagamentos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(auth()->user()->role === 'CLI')
    @php
        $client = auth()->user();
        $clientPets = $client->pets()->count();
        $clientAppointments = \App\Models\Agendamento::where('id_user_cliente', $client->id)->count();
        $clientPendingPayments = \App\Models\Pagamento::whereHas('agendamento', function ($query) use ($client) {
            $query->where('id_user_cliente', $client->id);
        })->where('status_pagamento', 'pendente')->count();
        $availableSitters = \App\Models\User::where('role', 'PRO')->whereHas('disponibilidades')->count();
        $clientUpcoming = \App\Models\Agendamento::with(['pet', 'sitter', 'servico'])
            ->where('id_user_cliente', $client->id)
            ->whereIn('status', ['pendente', 'confirmado'])
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio')
            ->limit(5)
            ->get();
    @endphp

    @push('styles')
    <style>
        .client-dashboard .client-hero {
            background: linear-gradient(135deg, #fff7ed 0%, #ffffff 70%);
            border: 1px solid #fed7aa;
        }
        .client-dashboard .client-stat {
            background: #fff;
            border: 1px solid #e7e5e4;
            border-radius: 14px;
            height: 100%;
            padding: 1.1rem;
        }
        .client-dashboard .client-stat-icon {
            align-items: center;
            background: #fff7ed;
            border-radius: 10px;
            color: #c2410c;
            display: inline-flex;
            height: 40px;
            justify-content: center;
            width: 40px;
        }
        .client-dashboard .client-stat-value {
            color: #1c1917;
            font-size: 1.5rem;
            font-weight: 800;
            line-height: 1;
        }
        .client-dashboard .client-stat-label {
            color: #78716c;
            font-size: .78rem;
        }
        .client-dashboard .client-section {
            background: #fff;
            border: 1px solid #e7e5e4;
            border-radius: 14px;
            overflow: hidden;
        }
        .client-dashboard .client-section-header {
            align-items: center;
            border-bottom: 1px solid #e7e5e4;
            display: flex;
            justify-content: space-between;
            padding: .95rem 1.1rem;
        }
        .client-dashboard .client-section-title {
            color: #292524;
            font-size: .92rem;
            font-weight: 700;
            margin: 0;
        }
        .client-dashboard .client-row {
            align-items: center;
            border-bottom: 1px solid #f5f5f4;
            display: flex;
            gap: .75rem;
            justify-content: space-between;
            padding: .85rem 1.1rem;
        }
        .client-dashboard .client-row:last-child { border-bottom: 0; }
        .client-dashboard .client-row-title { color: #292524; font-size: .84rem; font-weight: 700; }
        .client-dashboard .client-row-meta { color: #78716c; font-size: .76rem; }
        .client-dashboard .client-action {
            align-items: center;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            color: #c2410c;
            display: flex;
            font-size: .8rem;
            font-weight: 600;
            gap: .45rem;
            padding: .65rem .8rem;
            text-decoration: none;
        }
        .client-dashboard .client-action:hover { background: #ffedd5; color: #9a3412; }
        @media (max-width: 575.98px) {
            .client-dashboard .client-row { align-items: flex-start; flex-direction: column; }
        }
    </style>
    @endpush

    <div class="client-dashboard">
        <div class="client-hero rounded-4 p-4 mb-4">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="text-muted small mb-1">Minha área</div>
                    <h4 class="mb-2" style="color:#1C1917;">Olá, {{ $client->name }}!</h4>
                    <p class="text-muted mb-0" style="font-size:.88rem;">Cuide dos seus pets e acompanhe seus atendimentos.</p>
                </div>
                <i class="bi bi-heart-fill" style="font-size:2rem;color:#ea580c;"></i>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-heart"></i></div><div class="client-stat-value">{{ $clientPets }}</div><div class="client-stat-label">Meus pets</div></div></div>
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-calendar3"></i></div><div class="client-stat-value">{{ $clientAppointments }}</div><div class="client-stat-label">Meus agendamentos</div></div></div>
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-person-check"></i></div><div class="client-stat-value">{{ $availableSitters }}</div><div class="client-stat-label">Sitters disponíveis</div></div></div>
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-wallet2"></i></div><div class="client-stat-value">{{ $clientPendingPayments }}</div><div class="client-stat-label">Pagamentos pendentes</div></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="client-section">
                    <div class="client-section-header">
                        <h6 class="client-section-title"><i class="bi bi-calendar-event me-2 text-warning"></i>Próximos atendimentos</h6>
                        <a href="{{ route('agendamentos.index') }}" class="small text-decoration-none">Ver todos</a>
                    </div>
                    @forelse($clientUpcoming as $agendamento)
                        <div class="client-row">
                            <div>
                                <div class="client-row-title">{{ $agendamento->pet->nome ?? 'Pet' }} · {{ $agendamento->servico->nome ?? 'Serviço' }}</div>
                                <div class="client-row-meta">{{ $agendamento->data_inicio?->format('d/m/Y H:i') }} · Sitter: {{ $agendamento->sitter->name ?? '—' }}</div>
                            </div>
                            <span class="badge {{ $agendamento->status === 'confirmado' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($agendamento->status) }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted small">Você não possui próximos atendimentos.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="client-section h-100">
                    <div class="client-section-header"><h6 class="client-section-title"><i class="bi bi-lightning-charge me-2 text-warning"></i>Ações rápidas</h6></div>
                    <div class="p-3 d-grid gap-2">
                        <a href="{{ route('pets.create') }}" class="client-action"><i class="bi bi-plus-circle"></i>Adicionar pet</a>
                        <a href="{{ route('sitters.index') }}" class="client-action"><i class="bi bi-person-hearts"></i>Encontrar sitter</a>
                        <a href="{{ route('agendamentos.create') }}" class="client-action"><i class="bi bi-calendar-plus"></i>Novo agendamento</a>
                        <a href="{{ route('pagamentos.index') }}" class="client-action"><i class="bi bi-credit-card"></i>Ver pagamentos</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(auth()->user()->role === 'PRO')
    @php
        $pro = auth()->user();
        $proAppointments = \App\Models\Agendamento::where('id_user_sitter', $pro->id);
        $proPending = (clone $proAppointments)->where('status', 'pendente')->count();
        $proConfirmed = (clone $proAppointments)->where('status', 'confirmado')->count();
        $proCompleted = (clone $proAppointments)->where('status', 'concluido')->count();
        $proAverage = $pro->avaliacoesRecebidas()->avg('nota');
        $proServices = $pro->servicos()->count();
        $proAvailability = $pro->disponibilidades()->count();
        $proUpcoming = \App\Models\Agendamento::with(['cliente', 'pet', 'servico'])
            ->where('id_user_sitter', $pro->id)
            ->whereIn('status', ['pendente', 'confirmado'])
            ->where('data_inicio', '>=', now())
            ->orderBy('data_inicio')
            ->limit(5)
            ->get();
    @endphp

    <div class="client-dashboard pro-dashboard">
        <div class="client-hero rounded-4 p-4 mb-4">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="text-muted small mb-1">Minha área profissional</div>
                    <h4 class="mb-2" style="color:#1C1917;">Olá, {{ $pro->name }}!</h4>
                    <p class="text-muted mb-0" style="font-size:.88rem;">Acompanhe seus atendimentos e mantenha sua agenda organizada.</p>
                </div>
                <i class="bi bi-person-check-fill" style="font-size:2rem;color:#ea580c;"></i>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-hourglass-split"></i></div><div class="client-stat-value">{{ $proPending }}</div><div class="client-stat-label">Solicitações pendentes</div></div></div>
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-calendar-check"></i></div><div class="client-stat-value">{{ $proConfirmed }}</div><div class="client-stat-label">Atendimentos confirmados</div></div></div>
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-check2-circle"></i></div><div class="client-stat-value">{{ $proCompleted }}</div><div class="client-stat-label">Atendimentos concluídos</div></div></div>
            <div class="col-6 col-xl-3"><div class="client-stat"><div class="client-stat-icon mb-3"><i class="bi bi-star-fill"></i></div><div class="client-stat-value">{{ $proAverage ? number_format($proAverage, 1, ',', '.') : '—' }}</div><div class="client-stat-label">Avaliação média</div></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="client-section">
                    <div class="client-section-header">
                        <h6 class="client-section-title"><i class="bi bi-calendar-event me-2 text-warning"></i>Próximos atendimentos</h6>
                        <a href="{{ route('agendamentos.index') }}" class="small text-decoration-none">Ver todos</a>
                    </div>
                    @forelse($proUpcoming as $agendamento)
                        <div class="client-row">
                            <div>
                                <div class="client-row-title">{{ $agendamento->pet->nome ?? 'Pet' }} · {{ $agendamento->servico->nome ?? 'Serviço' }}</div>
                                <div class="client-row-meta">{{ $agendamento->data_inicio?->format('d/m/Y H:i') }} · Cliente: {{ $agendamento->cliente->name ?? '—' }}</div>
                            </div>
                            <span class="badge {{ $agendamento->status === 'confirmado' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($agendamento->status) }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted small">Nenhum próximo atendimento.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="client-section h-100">
                    <div class="client-section-header"><h6 class="client-section-title"><i class="bi bi-briefcase me-2 text-warning"></i>Meu trabalho</h6></div>
                    <div class="p-3">
                        <div class="client-row px-0"><span class="client-row-meta">Serviços cadastrados</span><strong>{{ $proServices }}</strong></div>
                        <div class="client-row px-0"><span class="client-row-meta">Horários disponíveis</span><strong>{{ $proAvailability }}</strong></div>
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('agendamentos.index') }}" class="client-action"><i class="bi bi-calendar3"></i>Gerenciar atendimentos</a>
                            <a href="{{ route('servicos.index') }}" class="client-action"><i class="bi bi-scissors"></i>Gerenciar serviços</a>
                            <a href="{{ route('disponibilidades.index') }}" class="client-action"><i class="bi bi-clock"></i>Gerenciar disponibilidade</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <!-- Cartão de Boas-vindas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="font-size: 2rem;">🐾</div>
                        <div>
                            <h5 class="card-title mb-0">Bem-vindo, {{ auth()->user()->name }}!</h5>
                            <p class="text-muted small mb-0">
                                @if(auth()->user()->role === 'ADM')
                                    Gerencie a plataforma PetConnect
                                @elseif(auth()->user()->role === 'CLI')
                                    Encontre e contrate profissionais para seus pets
                                @else
                                    Ofereça seus serviços de cuidado com animais
                                @endif
                            </p>
                        </div>
                    </div>
                    <p class="text-muted small">
                        @if(auth()->user()->role === 'ADM')
                            Use o painel de controle para gerenciar usuários, agendamentos, serviços e pagamentos da plataforma.
                        @elseif(auth()->user()->role === 'CLI')
                            Navegue pela plataforma para descobrir profissionais confiáveis que podem cuidar dos seus pets.
                        @else
                            Gerencie sua disponibilidade e receba solicitações de serviço dos clientes.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Estatísticas Rápidas -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="card-title mb-3">Resumo Rápido</h6>
                    <div class="row">
                        @if(auth()->user()->role === 'ADM')
                            <div class="col-6">
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">Total Usuários</p>
                                    <h4 class="mb-0" style="color: var(--pet-primary);">
                                        {{ \App\Models\User::count() }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">Agendamentos</p>
                                    <h4 class="mb-0" style="color: var(--pet-primary);">
                                        {{ \App\Models\Agendamento::count() }}
                                    </h4>
                                </div>
                            </div>
                        @elseif(auth()->user()->role === 'CLI')
                            <div class="col-6">
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">Meus Pets</p>
                                    <h4 class="mb-0" style="color: var(--pet-primary);">
                                        {{ auth()->user()->pets()->count() }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">Agendamentos</p>
                                    <h4 class="mb-0" style="color: var(--pet-primary);">
                                        {{ \App\Models\Agendamento::where('id_user_cliente', auth()->user()->id)->count() }}
                                    </h4>
                                </div>
                            </div>
                        @else
                            <div class="col-6">
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">Agendamentos</p>
                                    <h4 class="mb-0" style="color: var(--pet-primary);">
                                        {{ \App\Models\Agendamento::where('id_user_sitter', auth()->user()->id)->count() }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <p class="text-muted small mb-1">Avaliação Média</p>
                                    <h4 class="mb-0" style="color: var(--pet-primary);">
                                        {{ number_format(auth()->user()->avaliacoesRecebidas()->avg('nota') ?? 0, 1, ',', '.') }}
                                    </h4>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row mb-4">
        <div class="col-12">
            <h6 class="mb-3">Ações Rápidas</h6>
            <div class="d-flex gap-2 flex-wrap">
                @if(auth()->user()->role === 'ADM')
                    <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-people me-2"></i>Gerenciar Usuários
                    </a>
                    <a href="{{ route('servicos.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-scissors me-2"></i>Gerenciar Serviços
                    </a>
                    <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-calendar3 me-2"></i>Gerenciar Agendamentos
                    </a>
                    <a href="{{ route('especies.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-tags me-2"></i>
                        Gerenciar Espécies
                    </a>
                    <a href="{{ route('racas.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-diagram-3 me-2"></i>
                    Gerenciar Raças
                    </a>
                @elseif(auth()->user()->role === 'CLI')
                    <a href="{{ route('agendamentos.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>Novo Agendamento
                    </a>
                    <a href="{{ route('pets.create') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle me-2"></i>Adicionar Pet
                    </a>
                    <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-calendar3 me-2"></i>Ver Agendamentos
                    </a>
                @else
                    <a href="{{ route('disponibilidades.index') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-clock me-2"></i>Gerenciar Disponibilidade
                    </a>
                    <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-calendar3 me-2"></i>Ver Agendamentos
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Últimos Agendamentos -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 p-3">
                    <h6 class="mb-0">Últimos Agendamentos</h6>
                </div>
                <div class="card-body p-0">
                    @php
                        if(auth()->user()->role === 'ADM') {
                            $agendamentos = \App\Models\Agendamento::latest()->limit(5)->get();
                        } elseif(auth()->user()->role === 'CLI') {
                            $agendamentos = \App\Models\Agendamento::where('id_user_cliente', auth()->user()->id)->latest()->limit(5)->get();
                        } else {
                            $agendamentos = \App\Models\Agendamento::where('id_user_sitter', auth()->user()->id)->latest()->limit(5)->get();
                        }
                    @endphp

                    @if($agendamentos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>
                                            @if(auth()->user()->role === 'ADM')
                                                Cliente
                                            @elseif(auth()->user()->role === 'CLI')
                                                Profissional
                                            @else
                                                Pet do Cliente
                                            @endif
                                        </th>
                                        <th>Serviço</th>
                                        <th>Status</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($agendamentos as $agendamento)
                                        <tr>
                                            <td>
                                                <small>{{ $agendamento->data_inicio->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                @if(auth()->user()->role === 'ADM')
                                                    {{ $agendamento->cliente->name ?? 'N/A' }}
                                                @elseif(auth()->user()->role === 'CLI')
                                                    {{ $agendamento->sitter->name ?? 'N/A' }}
                                                @else
                                                    {{ $agendamento->pet->nome ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td>{{ $agendamento->servico->nome ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match($agendamento->status) {
                                                        'pendente' => 'warning',
                                                        'confirmado' => 'success',
                                                        'cancelado' => 'danger',
                                                        'concluido' => 'info',
                                                        default => 'secondary'
                                                    };
                                                    $statusLabel = match($agendamento->status) {
                                                        'pendente' => 'Pendente',
                                                        'confirmado' => 'Confirmado',
                                                        'cancelado' => 'Cancelado',
                                                        'concluido' => 'Concluído',
                                                        default => ucfirst($agendamento->status)
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('agendamentos.show', $agendamento->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4 text-center text-muted">
                            <p class="mb-0">Nenhum agendamento ainda. Comece a usar a plataforma!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
