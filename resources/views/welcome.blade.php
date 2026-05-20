@extends('layout')

@section('title', 'Dashboard - PetConnect')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
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
                                        {{ number_format(\App\Models\Avaliacao::where('id_user', auth()->user()->id)->avg('nota') ?? 0, 1) }}
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
@endsection
